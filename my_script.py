#!/usr/bin/env python3
import sys
import json
import os
from openai import OpenAI
from dotenv import load_dotenv

# Load environment variable from .env
load_dotenv("/home/users/raikkulenz/sites/raikkulenz.kapsi.fi/.env")
api_key = os.getenv("OPENAI_API_KEY")
client = OpenAI(api_key=api_key)
print("API Key starts with:", os.getenv("OPENAI_API_KEY")[:8], file=sys.stderr)
# Accept arguments from PHP
arg1 = sys.argv[1] if len(sys.argv) > 1 else 'default1'
arg2 = sys.argv[2] if len(sys.argv) > 2 else ''

# Compose the prompt
user_input = f"{arg1} {arg2}".strip()

# Call ChatGPT
try:
    response = client.chat.completions.create(
        model="gpt-4",
        messages=[
            {"role": "system", "content": "You are a helpful electronics assistant."},
            {"role": "user", "content": user_input}
        ]
    )
    answer = response.choices[0].message.content.strip()
except Exception as e:
    answer = f"Error: {str(e)}"

# Prepare result as JSON
result = {
    'input_1': arg1,
    'input_2': arg2,
    'summary': f"Received: '{user_input}'",
    'gpt_response': answer,
    'lengths': {
        'input_1_length': len(arg1),
        'input_2_length': len(arg2)
    }
}

# Output JSON back to PHP
print(json.dumps(result, ensure_ascii=False))
