#!/usr/bin/env python3
"""
Flask OCR Service for Philippine ID Name Extraction
Handles both text processing and image OCR with name extraction
"""

import os
import io
import re
import sys
import json
import base64
import logging
import time
from datetime import datetime
from typing import List, Dict, Any, Optional, Tuple

import cv2
import numpy as np
from PIL import Image
import easyocr
import pytesseract
from flask import Flask, request, jsonify
from flask_cors import CORS

# Configure logging
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s'
)
logger = logging.getLogger(__name__)

app = Flask(__name__)
CORS(app)

class NameExtractor:
    """Extract Filipino names from text using multiple strategies"""

    def __init__(self):
        # Common Filipino name patterns and prefixes
        self.surname_prefixes = [
            'DELA', 'DE LA', 'DEL', 'DE LOS', 'DE LAS',
            'SAN', 'SANTA', 'SANTO', 'MC', 'MAC'
        ]

        # Common Filipino suffixes
        self.name_suffixes = ['JR', 'SR', 'III', 'IV', 'V', 'II']

        # Common non-name words to filter out
        self.stop_words = {
            'REPUBLIC', 'PHILIPPINES', 'PILIPINAS', 'PAMBANSANG', 'PAGKAKAKILANLAN',
            'NATIONAL', 'ID', 'IDENTIFICATION', 'CARD', 'GOVERNMENT', 'ISSUED',
            'PHILSYS', 'PCN', 'DATE', 'BIRTH', 'SEX', 'ADDRESS', 'SIGNATURE',
            'VALID', 'UNTIL', 'EXPIRE', 'EXPIRY', 'PLACE', 'ISSUED', 'AUTHORITY',
            'MALE', 'FEMALE', 'M', 'F', 'MARITAL', 'STATUS', 'SINGLE', 'MARRIED',
            'WIDOWED', 'DIVORCED', 'CITIZENSHIP', 'FILIPINO', 'BLOOD', 'TYPE',
            'HEIGHT', 'WEIGHT', 'RESTRICTIONS', 'CONDITIONS', 'LICENSE', 'NUMBER'
        }

    def extract_names(self, text: str) -> List[str]:
        """Extract potential Filipino names from text"""
        if not text or not isinstance(text, str):
            return []

        # Clean and normalize text
        text = self._clean_text(text)

        # Extract potential names using multiple methods
        potential_names = []

        # Method 1: Field-based extraction
        field_names = self._extract_by_fields(text)
        potential_names.extend(field_names)

        # Method 2: Pattern-based extraction
        pattern_names = self._extract_by_patterns(text)
        potential_names.extend(pattern_names)

        # Method 3: Structure-based extraction
        structure_names = self._extract_by_structure(text)
        potential_names.extend(structure_names)

        # Filter and clean results
        filtered_names = self._filter_and_clean_names(potential_names)

        # Remove duplicates while preserving order
        unique_names = []
        seen = set()
        for name in filtered_names:
            name_upper = name.upper()
            if name_upper not in seen:
                unique_names.append(name)
                seen.add(name_upper)

        logger.info(f"Extracted {len(unique_names)} unique names from text")
        return unique_names

    def _clean_text(self, text: str) -> str:
        """Clean and normalize text for processing"""
        # Remove extra whitespace and normalize
        text = re.sub(r'\s+', ' ', text.strip())

        # Remove common OCR artifacts
        text = re.sub(r'[^\w\s\-\'\.]', ' ', text)

        return text

    def _extract_by_fields(self, text: str) -> List[str]:
        """Extract names based on form field labels"""
        names = []
        lines = text.split('\n')

        field_patterns = [
            r'(?:APELYIDO|SURNAME|LAST\s*NAME|FAMILY\s*NAME)[\s:]*([A-Z][A-Z\s\-\'\.]+)',
            r'(?:PANGALAN|GIVEN\s*NAME|FIRST\s*NAME)[\s:]*([A-Z][A-Z\s\-\'\.]+)',
            r'(?:GITNANG\s*PANGALAN|MIDDLE\s*NAME)[\s:]*([A-Z][A-Z\s\-\'\.]+)',
            r'(?:BUONG\s*PANGALAN|FULL\s*NAME)[\s:]*([A-Z][A-Z\s\-\'\.]+)',
        ]

        for line in lines:
            for pattern in field_patterns:
                matches = re.finditer(pattern, line.upper())
                for match in matches:
                    name = match.group(1).strip()
                    if self._is_valid_name(name):
                        names.append(name)

        return names

    def _extract_by_patterns(self, text: str) -> List[str]:
        """Extract names using common Filipino name patterns"""
        names = []

        # Pattern for names with surname prefixes
        prefix_pattern = r'\b(?:' + '|'.join(self.surname_prefixes) + r')\s+[A-Z][A-Z\s\-\']+\b'
        matches = re.finditer(prefix_pattern, text.upper())
        for match in matches:
            name = match.group().strip()
            if self._is_valid_name(name):
                names.append(name)

        # Pattern for capitalized words (potential names)
        word_pattern = r'\b[A-Z][A-Z\s\-\']{2,}\b'
        matches = re.finditer(word_pattern, text.upper())
        for match in matches:
            name = match.group().strip()
            if self._is_valid_name(name) and len(name.split()) <= 4:
                names.append(name)

        return names

    def _extract_by_structure(self, text: str) -> List[str]:
        """Extract names based on document structure"""
        names = []
        lines = [line.strip() for line in text.split('\n') if line.strip()]

        # Look for lines that appear to be names (multiple capitalized words)
        for line in lines:
            words = line.split()
            if len(words) >= 2 and len(words) <= 5:
                # Check if all words look like names
                if all(self._looks_like_name_word(word) for word in words):
                    if self._is_valid_name(line):
                        names.append(line.upper())

        return names

    def _looks_like_name_word(self, word: str) -> bool:
        """Check if a word looks like part of a name"""
        word = word.upper()

        # Skip obvious non-names
        if word in self.stop_words:
            return False

        # Must start with letter
        if not re.match(r'^[A-Z]', word):
            return False

        # Should be mostly letters
        if not re.match(r'^[A-Z\-\'\.]+$', word):
            return False

        # Reasonable length
        if len(word) < 2 or len(word) > 20:
            return False

        return True

    def _is_valid_name(self, name: str) -> bool:
        """Check if extracted text is a valid name"""
        if not name or len(name.strip()) < 2:
            return False

        name = name.strip().upper()

        # Skip if contains stop words
        name_words = name.split()
        for word in name_words:
            if word in self.stop_words:
                return False

        # Skip pure numbers or dates
        if re.match(r'^\d+$', name) or re.match(r'\d{1,2}[\/\-]\d{1,2}[\/\-]\d{2,4}', name):
            return False

        # Skip if too many numbers
        if len(re.findall(r'\d', name)) > len(name) // 3:
            return False

        # Should contain mostly letters
        if not re.match(r'^[A-Z\s\-\'\.]+$', name):
            return False

        # Reasonable length
        if len(name) < 2 or len(name) > 100:
            return False

        return True

    def _filter_and_clean_names(self, names: List[str]) -> List[str]:
        """Filter and clean extracted names"""
        cleaned_names = []

        for name in names:
            if not name:
                continue

            # Clean the name
            name = name.strip().upper()
            name = re.sub(r'\s+', ' ', name)  # Normalize spaces

            # Final validation
            if self._is_valid_name(name):
                cleaned_names.append(name)

        # Sort by length (longer names first, as they're often more complete)
        cleaned_names.sort(key=len, reverse=True)

        return cleaned_names

class ImageProcessor:
    """Handle image processing and OCR"""

    def __init__(self):
        # Initialize EasyOCR reader (supports English and Filipino)
        try:
            self.reader = easyocr.Reader(['en'], gpu=False)  # Start with English, add 'tl' if Tagalog models are available
            logger.info("EasyOCR initialized successfully")
        except Exception as e:
            logger.error(f"Failed to initialize EasyOCR: {e}")
            self.reader = None

    def process_image(self, image_data: str) -> Tuple[str, Dict[str, Any]]:
        """Process image and extract text using multiple OCR methods"""
        start_time = time.time()

        try:
            # Decode base64 image
            image = self._decode_base64_image(image_data)

            # Preprocess image for better OCR
            processed_image = self._preprocess_image(image)

            # Extract text using multiple methods
            ocr_results = []

            # Method 1: EasyOCR
            if self.reader:
                easy_result = self._extract_with_easyocr(processed_image)
                if easy_result:
                    ocr_results.append(('EasyOCR', easy_result))

            # Method 2: Tesseract
            tesseract_result = self._extract_with_tesseract(processed_image)
            if tesseract_result:
                ocr_results.append(('Tesseract', tesseract_result))

            # Combine and clean results
            combined_text = self._combine_ocr_results(ocr_results)

            processing_time = time.time() - start_time

            metadata = {
                'processing_time': round(processing_time, 2),
                'ocr_methods': len(ocr_results),
                'image_size': image.shape[:2] if hasattr(image, 'shape') else None
            }

            logger.info(f"Image processed in {processing_time:.2f}s using {len(ocr_results)} OCR methods")

            return combined_text, metadata

        except Exception as e:
            logger.error(f"Image processing failed: {e}")
            return "", {"error": str(e)}

    def _decode_base64_image(self, image_data: str) -> np.ndarray:
        """Decode base64 image data"""
        try:
            # Remove data URL prefix if present
            if image_data.startswith('data:image'):
                image_data = image_data.split(',')[1]

            # Decode base64
            image_bytes = base64.b64decode(image_data)

            # Convert to PIL Image
            pil_image = Image.open(io.BytesIO(image_bytes))

            # Convert to OpenCV format
            cv_image = cv2.cvtColor(np.array(pil_image), cv2.COLOR_RGB2BGR)

            return cv_image

        except Exception as e:
            logger.error(f"Failed to decode image: {e}")
            raise

    def _preprocess_image(self, image: np.ndarray) -> np.ndarray:
        """Preprocess image for better OCR results"""
        try:
            # Convert to grayscale
            if len(image.shape) == 3:
                gray = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)
            else:
                gray = image

            # Apply denoising
            denoised = cv2.fastNlMeansDenoising(gray)

            # Enhance contrast
            enhanced = cv2.convertScaleAbs(denoised, alpha=1.2, beta=10)

            # Apply adaptive thresholding
            binary = cv2.adaptiveThreshold(
                enhanced, 255, cv2.ADAPTIVE_THRESH_GAUSSIAN_C,
                cv2.THRESH_BINARY, 11, 2
            )

            return binary

        except Exception as e:
            logger.error(f"Image preprocessing failed: {e}")
            return image

    def _extract_with_easyocr(self, image: np.ndarray) -> Optional[str]:
        """Extract text using EasyOCR"""
        try:
            if not self.reader:
                return None

            results = self.reader.readtext(image)
            text_parts = []

            for (bbox, text, confidence) in results:
                if confidence > 0.3:  # Filter low-confidence results
                    text_parts.append(text)

            return '\n'.join(text_parts)

        except Exception as e:
            logger.error(f"EasyOCR extraction failed: {e}")
            return None

    def _extract_with_tesseract(self, image: np.ndarray) -> Optional[str]:
        """Extract text using Tesseract"""
        try:
            # Configure Tesseract for better results
            config = '--oem 3 --psm 6 -c tessedit_char_whitelist=ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789 .,/-'

            text = pytesseract.image_to_string(image, config=config)
            return text.strip()

        except Exception as e:
            logger.error(f"Tesseract extraction failed: {e}")
            return None

    def _combine_ocr_results(self, results: List[Tuple[str, str]]) -> str:
        """Combine results from multiple OCR methods"""
        if not results:
            return ""

        # For now, use the first successful result
        # In the future, you could implement more sophisticated combination logic
        for method, text in results:
            if text and text.strip():
                logger.info(f"Using text from {method}")
                return text.strip()

        return ""

# Initialize services
name_extractor = NameExtractor()
image_processor = ImageProcessor()

@app.route('/health', methods=['GET'])
def health_check():
    """Health check endpoint"""
    return jsonify({
        'status': 'healthy',
        'timestamp': datetime.now().isoformat(),
        'services': {
            'easyocr': image_processor.reader is not None,
            'tesseract': True  # Assume tesseract is available if we got this far
        }
    })

@app.route('/status', methods=['GET'])
def get_status():
    """Detailed status endpoint"""
    return jsonify({
        'status': 'running',
        'timestamp': datetime.now().isoformat(),
        'version': '1.0.0',
        'services': {
            'easyocr': image_processor.reader is not None,
            'tesseract': True,
            'opencv': True,
            'pillow': True
        },
        'supported_operations': [
            'extract-names',
            'extract-names-from-image'
        ]
    })

@app.route('/extract-names', methods=['POST'])
def extract_names():
    """Extract names from provided text"""
    try:
        data = request.get_json()

        if not data or 'text' not in data:
            return jsonify({
                'success': False,
                'error': 'Text field is required'
            }), 400

        text = data['text']

        if not isinstance(text, str) or not text.strip():
            return jsonify({
                'success': False,
                'error': 'Text must be a non-empty string'
            }), 400

        # Extract names
        names = name_extractor.extract_names(text)

        return jsonify({
            'success': True,
            'names': names,
            'count': len(names),
            'original_text': text[:500] + '...' if len(text) > 500 else text
        })

    except Exception as e:
        logger.error(f"Name extraction failed: {e}")
        return jsonify({
            'success': False,
            'error': str(e)
        }), 500

@app.route('/extract-names-from-image', methods=['POST'])
def extract_names_from_image():
    """Extract names directly from image using OCR"""
    try:
        data = request.get_json()

        if not data or 'image' not in data:
            return jsonify({
                'success': False,
                'error': 'Image field is required'
            }), 400

        image_data = data['image']

        if not isinstance(image_data, str) or not image_data.strip():
            return jsonify({
                'success': False,
                'error': 'Image must be a non-empty base64 string'
            }), 400

        # Process image and extract text
        extracted_text, metadata = image_processor.process_image(image_data)

        if not extracted_text:
            return jsonify({
                'success': False,
                'error': 'No text could be extracted from the image',
                'extracted_text': '',
                'names': [],
                'metadata': metadata
            })

        # Extract names from the OCR text
        names = name_extractor.extract_names(extracted_text)

        return jsonify({
            'success': True,
            'extracted_text': extracted_text,
            'names': names,
            'count': len(names),
            'processing_time': metadata.get('processing_time'),
            'metadata': metadata
        })

    except Exception as e:
        logger.error(f"Image OCR processing failed: {e}")
        return jsonify({
            'success': False,
            'error': str(e),
            'extracted_text': '',
            'names': []
        }), 500

if __name__ == '__main__':
    logger.info("Starting Flask OCR Service...")
    logger.info("Available endpoints:")
    logger.info("  GET  /health - Health check")
    logger.info("  GET  /status - Detailed status")
    logger.info("  POST /extract-names - Extract names from text")
    logger.info("  POST /extract-names-from-image - Extract names from image")

    app.run(
        host='0.0.0.0',  # Use 0.0.0.0 to accept connections from other services
        port=5000,
        debug=False,
        threaded=True,
        use_reloader=False
    )
