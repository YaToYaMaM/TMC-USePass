#!/usr/bin/env python3
import sys
import json
import re

def extract_names_from_text(text):
    """Extract potential names from text using regex patterns."""
    name_patterns = [
        r'\b[A-Z][a-z]+(?:\s+[A-Z][a-z]+)*\b',  # Basic capitalized names
        r'\b[A-Z][a-z]+(?:\s+[A-Z]\.)*(?:\s+[A-Z][a-z]+)+\b'  # Names with middle initials
    ]

    names = set()

    for pattern in name_patterns:
        matches = re.findall(pattern, text)
        for match in matches:
            # Filter out common words that aren't names
            words_to_exclude = {
                'The', 'This', 'That', 'These', 'Those', 'And', 'Or', 'But',
                'When', 'Where', 'What', 'Who', 'Why', 'How', 'If', 'Then',
                'Server', 'Press', 'Ctrl', 'INFO', 'Configuration', 'Directory',
                'Mode', 'LastWriteTime', 'Length', 'Name', 'Microsoft', 'Corporation',
                'PowerShell', 'PhpstormProjects', 'UserAgent'
            }

            if len(match) > 2 and match not in words_to_exclude:
                names.add(match)

    return list(names)

def main():
    if len(sys.argv) != 2:
        print(json.dumps({
            'success': False,
            'error': 'Usage: python direct_extractor.py <input_file>'
        }))
        sys.exit(1)

    input_file = sys.argv[1]

    try:
        with open(input_file, 'r', encoding='utf-8') as f:
            text = f.read()

        names = extract_names_from_text(text)

        result = {
            'success': True,
            'names': names,
            'count': len(names)
        }

        print(json.dumps(result))

    except Exception as e:
        print(json.dumps({
            'success': False,
            'error': str(e)
        }))
        sys.exit(1)

if __name__ == '__main__':
    main()
