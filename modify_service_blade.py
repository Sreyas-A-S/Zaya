import re
import os

file_path = r'c:\wamp64\www\zaya\resources\views\admin\services\index.blade.php'

with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Pattern to find the OLD Media Manager block
# Key identifying feature: "Service Multimedia" label and "Select multiple images" text
pattern = r'<!-- Multimedia Manager -->\s*<div class="col-12">\s*<label class="form-label fw-bold">Service Multimedia</label>[\s\S]*?<!-- Grid Container -->[\s\S]*?</div>\s*</div>\s*</div>'

# We can match slightly looser to be safe
# Start: <!-- Multimedia Manager -->
# End: </div> (closing col-12)
# Internal: "Service Multimedia"

# Let's find the specific block structure
# It starts at <!-- Multimedia Manager --> and ends before <!-- Hidden Inputs for logic -->
p = r'(<!-- Multimedia Manager -->\s*<div class="col-12">\s*<label class="form-label fw-bold">Service Multimedia</label>[\s\S]*?)(?=<!-- Hidden Inputs for logic -->)'

# Replace with empty string
new_content = re.sub(p, '', content, count=1)

if new_content == content:
    print("No replacement made. Pattern mismatch.")
    # Debug: print what we found around that area
    idx = content.find("Service Multimedia")
    if idx != -1:
        print("Found 'Service Multimedia' at index", idx)
        print("Context:", content[idx-100:idx+200])
else:
    with open(file_path, 'w', encoding='utf-8') as f:
        f.write(new_content)
    print("Successfully removed old Media Manager block.")
