import re
import json
import os

def update_blade_and_json():
    blade_path = r'c:\wamp64\www\zaya\resources\views\practitioner-register.blade.php'
    json_path = r'c:\wamp64\www\zaya\lang\fr.json'

    with open(blade_path, 'r', encoding='utf-8') as f:
        html = f.read()

    # List of English texts to translate
    texts = [
        "Elevate Your Practice. Join the ZAYA Collective",
        "Become a part of a specialized ecosystem where tradition meets technology. Complete your registration to showcase your expertise, manage your global clientele and help us redefine holistic wellness.",
        "Practitioner Registration Form",
        "Basic Details",
        "Qualifications",
        "Verification",
        "First Name",
        "Enter First Name",
        "Last Name",
        "Enter Last Name",
        "Add Photo",
        "Gender",
        "Male",
        "Female",
        "Other",
        "Email",
        "Enter Email",
        "Phone No.",
        "Enter Phone No.",
        "Password",
        "Enter Password",
        "Confirm Password",
        "DOB",
        "DD/MM/YYYY",
        "Country",
        "Select Country",
        "Address Line 1",
        "Enter Address Line 1",
        "Address Line 2",
        "Enter Address Line 2",
        "Website",
        "(if any)",
        "Enter URL",
        "City",
        "Enter City",
        "State",
        "Enter State",
        "Zip Code",
        "Enter Zip Code",
        "Education",
        "Add Another Education",
        "Education Type",
        "Select",
        "Degree",
        "Diploma",
        "Certification",
        "Institution Name",
        "Enter Institution Name",
        "Duration",
        "(Hours/Years)",
        "Enter Duration",
        "Professional Bio",
        "Write your Professional Bio...",
        "Professional Practice Details",
        "Ayurvedic Wellness Consultation:",
        "Focuses on nutritional and lifestyle guidance rooted in Ayurvedic principles:",
        "Choose your practice areas",
        "Please specify other practice area",
        "Massage & Body Therapists:",
        "Includes specific traditional physical treatments and specialized care:",
        "Please specify other therapy",
        "Other Modalities:",
        "Please specify other modality",
        "Add Summary",
        "E.g Outline your background in Ayurveda, yoga, sports or holistic wellness",
        "Certifications",
        "(Kindly include hours and dates. It should be self-attested)",
        "+ Add More Certificates",
        "ID Proof",
        "Training / Diploma",
        "Experience",
        "Upload",
        "(Max 2MB)",
        "Registration Form",
        "Code of Ethics",
        "Wellness Contract",
        "Upload Cover Letter",
        "Choose Images or documents",
        "JPG, JPEG, PNG, WEBP, DOC & PDF (Max.20MB)",
        "Cancel",
        "Save",
        "Languages Known",
        "Select Language",
        "Read",
        "Write",
        "Speak",
        "Listen",
        "Registration Fee Amount",
        "Pay",
        "Promocode",
        "Apply",
        "Captcha Verification",
        "Enter Code",
        "← Back to Website",
        "Save & Continue",
        "Thank you!",
        "Your Application Submitted!",
        "Your application will be reviewed within 20 days.",
        "Stay connect with us!",
        "Crop Profile Photo",
        "Crop & Save",
        "Back",
        "Submit",
        "Education #",
        "Remove",
        "Certification #",
        "Professional Practice Details",
    ]

    translations = {
        "Elevate Your Practice. Join the ZAYA Collective": "Élevez votre pratique. Rejoignez le collectif ZAYA",
        "Become a part of a specialized ecosystem where tradition meets technology. Complete your registration to showcase your expertise, manage your global clientele and help us redefine holistic wellness.": "Faites partie d'un écosystème spécialisé où la tradition rencontre la technologie. Complétez votre inscription pour présenter votre expertise, gérer votre clientèle mondiale et nous aider à redéfinir le bien-être holistique.",
        "Practitioner Registration Form": "Formulaire d'inscription des praticiens",
        "Basic Details": "Détails de base",
        "Qualifications": "Qualifications",
        "Verification": "Vérification",
        "First Name": "Prénom",
        "Enter First Name": "Entrez le prénom",
        "Last Name": "Nom de famille",
        "Enter Last Name": "Entrez le nom de famille",
        "Add Photo": "Ajouter une photo",
        "Gender": "Genre",
        "Male": "Homme",
        "Female": "Femme",
        "Other": "Autre",
        "Email": "E-mail",
        "Enter Email": "Entrez l'e-mail",
        "Phone No.": "N° de téléphone",
        "Enter Phone No.": "Entrez le n° de téléphone",
        "Password": "Mot de passe",
        "Enter Password": "Entrez le mot de passe",
        "Confirm Password": "Confirmez le mot de passe",
        "DOB": "Date de naissance",
        "DD/MM/YYYY": "JJ/MM/AAAA",
        "Country": "Pays",
        "Select Country": "Choisissez le pays",
        "Address Line 1": "Adresse Ligne 1",
        "Enter Address Line 1": "Entrez l'adresse ligne 1",
        "Address Line 2": "Adresse Ligne 2",
        "Enter Address Line 2": "Entrez l'adresse ligne 2",
        "Website": "Site web",
        "(if any)": "(le cas échéant)",
        "Enter URL": "Entrez l'URL",
        "City": "Ville",
        "Enter City": "Entrez la ville",
        "State": "État",
        "Enter State": "Entrez l'État",
        "Zip Code": "Code postal",
        "Enter Zip Code": "Entrez le code postal",
        "Education": "Éducation",
        "Add Another Education": "Ajouter une autre formation",
        "Education Type": "Type d'éducation",
        "Select": "Sélectionner",
        "Degree": "Diplôme universitaire",
        "Diploma": "Diplôme",
        "Certification": "Certification",
        "Institution Name": "Nom de l'établissement",
        "Enter Institution Name": "Entrez le nom de l'établissement",
        "Duration": "Durée",
        "(Hours/Years)": "(Heures/Années)",
        "Enter Duration": "Entrez la durée",
        "Professional Bio": "Biographie professionnelle",
        "Write your Professional Bio...": "Écrivez votre biographie professionnelle...",
        "Professional Practice Details": "Détails de la pratique professionnelle",
        "Ayurvedic Wellness Consultation:": "Consultation bien-être ayurvédique :",
        "Focuses on nutritional and lifestyle guidance rooted in Ayurvedic principles:": "Se concentre sur des conseils nutritionnels et de mode de vie ancrés dans les principes ayurvédiques :",
        "Choose your practice areas": "Choisissez vos domaines de pratique",
        "Please specify other practice area": "Veuillez préciser un autre domaine de pratique",
        "Massage & Body Therapists:": "Massothérapeutes et thérapeutes corporels :",
        "Includes specific traditional physical treatments and specialized care:": "Comprend des traitements physiques traditionnels spécifiques et des soins spécialisés :",
        "Please specify other therapy": "Veuillez préciser une autre thérapie",
        "Other Modalities:": "Autres modalités :",
        "Please specify other modality": "Veuillez préciser une autre modalité",
        "Add Summary": "Ajouter un résumé",
        "E.g Outline your background in Ayurveda, yoga, sports or holistic wellness": "Par exemple, décrivez votre expérience en Ayurveda, yoga, sport ou bien-être holistique",
        "Certifications": "Certifications",
        "(Kindly include hours and dates. It should be self-attested)": "(Veuillez inclure les heures et les dates. Il doit être auto-attesté)",
        "+ Add More Certificates": "+ Ajouter plus de certificats",
        "ID Proof": "Preuve d'identité",
        "Training / Diploma": "Formation / Diplôme",
        "Experience": "Expérience",
        "Upload": "Télécharger",
        "(Max 2MB)": "(Max 2 Mo)",
        "Registration Form": "Formulaire d'inscription",
        "Code of Ethics": "Code d'éthique",
        "Wellness Contract": "Contrat de bien-être",
        "Upload Cover Letter": "Télécharger la lettre de motivation",
        "Choose Images or documents": "Choisissez des images ou des documents",
        "JPG, JPEG, PNG, WEBP, DOC & PDF (Max.20MB)": "JPG, JPEG, PNG, WEBP, DOC & PDF (Max. 20 Mo)",
        "Cancel": "Annuler",
        "Save": "Enregistrer",
        "Languages Known": "Langues parlées",
        "Select Language": "Choisir la langue",
        "Read": "Lire",
        "Write": "Écrire",
        "Speak": "Parler",
        "Listen": "Écouter",
        "Registration Fee Amount": "Montant des frais d'inscription",
        "Pay": "Payer",
        "Promocode": "Code promo",
        "Apply": "Appliquer",
        "Captcha Verification": "Vérification Captcha",
        "Enter Code": "Entrez le code",
        "← Back to Website": "← Retour au site",
        "Save & Continue": "Enregistrer et continuer",
        "Thank you!": "Merci !",
        "Your Application Submitted!": "Votre candidature a été soumise !",
        "Your application will be reviewed within 20 days.": "Votre candidature sera examinée dans un délai de 20 jours.",
        "Stay connect with us!": "Restez en contact avec nous !",
        "Crop Profile Photo": "Recadrer la photo de profil",
        "Crop & Save": "Recadrer et enregistrer",
        "Back": "Retour",
        "Submit": "Soumettre",
        "Education #": "Éducation #",
        "Remove": "Supprimer",
        "Certification #": "Certification #",
    }

    # Normalize whitespace function
    def norm(s):
        return ' '.join(s.split())

    # Replace function that handles multiline and whitespace
    def replace_smart(content, text, replacement_key):
        # 1. Inside tags: > Text <
        # Use regex to find text between tags, allowing for whitespace and newlines
        # We use a non-greedy .*? to avoid matching across multiple tags
        pattern1 = re.compile(r'(?<=>)(\s*)(' + re.escape(text).replace(r'\ ', r'\s+') + r')(\s*)(?=<)', re.IGNORECASE | re.DOTALL)
        content = pattern1.sub(r'\1{{ __("' + replacement_key + r'") }}\3', content)
        
        # 2. Inside attributes: placeholder="Text", title="Text"
        for attr in ['placeholder', 'title']:
            pattern_attr = re.compile(r'(' + attr + r'=")(\s*)(' + re.escape(text).replace(r'\ ', r'\s+') + r')(\s*)"', re.IGNORECASE | re.DOTALL)
            content = pattern_attr.sub(r'\1\2{{ __("' + replacement_key + r'") }}\4"', content)

        # 3. Inside option tag
        pattern_opt = re.compile(r'(<option[^>]*>)(\s*)(' + re.escape(text).replace(r'\ ', r'\s+') + r')(\s*)(</option>)', re.IGNORECASE | re.DOTALL)
        content = pattern_opt.sub(r'\1\2{{ __("' + replacement_key + r'") }}\4\5', content)

        return content

    # Sort texts by length descending to replace longer strings first
    texts.sort(key=len, reverse=True)

    # Clean up from previous run if necessary
    html = html.replace("\\'", "'")

    for text in texts:
        html = replace_smart(html, text, text)

    # Manual fixes for things that regex might have messed up or missed
    # Like nested tags or specific structures
    html = html.replace("{{ __('Website') }} <span\n                                    class=\"text-gray-400 italic\">{{ __('(if any)') }}</span>", 
                        "{{ __('Website') }} <span class=\"text-gray-400 italic\">{{ __('(if any)') }}</span>")
    
    # Fix the Education Type label which was split
    html = re.sub(r'label class="block text-\[#525252\] text-lg font-normal mb-3">Education\s+Type</label>', 
                  r'label class="block text-[#525252] text-lg font-normal mb-3">{{ __(\'Education Type\') }}</label>', html)

    with open(blade_path, 'w', encoding='utf-8') as f:
        f.write(html)

    # Update fr.json
    try:
        with open(json_path, 'r', encoding='utf-8') as f:
            fr_json = json.load(f)
    except:
        fr_json = {}

    for k, v in translations.items():
        if k not in fr_json:
            fr_json[k] = v

    with open(json_path, 'w', encoding='utf-8') as f:
        json.dump(fr_json, f, ensure_ascii=False, indent=4)

    print("HTML modified and FR json updated.")

if __name__ == "__main__":
    update_blade_and_json()
