import os
import sys
import pickle
import argparse
import cv2
import face_recognition

BASE_DIR = os.path.abspath(os.path.join(os.path.dirname(__file__), '..'))
TRAINER_DIR = os.path.join(BASE_DIR, 'trainer')
ENCODINGS_PATH = os.path.join(TRAINER_DIR, 'encodings.pickle')

TOLERANCE = 0.6

def load_encodings():
    if not os.path.exists(ENCODINGS_PATH):
        print("ERROR: File encoding tidak ditemukan. Jalankan train.py terlebih dahulu.")
        sys.exit(1)

    with open(ENCODINGS_PATH, 'rb') as f:
        data = pickle.load(f)
    return data

def main():
    parser = argparse.ArgumentParser(description='Kenali wajah dari sebuah gambar statis.')
    parser.add_argument('--image', required=True, help='Path ke file gambar')
    args = parser.parse_args()

    if not os.path.exists(args.image):
        print(f"ERROR: File gambar tidak ditemukan di path: {args.image}")
        sys.exit(1)

    encodings_data = load_encodings()
    known_encodings = encodings_data['encodings']
    known_names = encodings_data['names']

    # Load image
    image = cv2.imread(args.image)
    if image is None:
        print("ERROR: Gagal membaca file gambar.")
        sys.exit(1)

    # Convert BGR to RGB
    rgb_image = cv2.cvtColor(image, cv2.COLOR_BGR2RGB)
    
    # Detect faces
    face_locations = face_recognition.face_locations(rgb_image)
    face_encodings = face_recognition.face_encodings(rgb_image, face_locations)

    if len(face_encodings) == 0:
        print("UNKNOWN")
        sys.exit(0)

    # We assume there's one primary face, or we just check the first one
    # For a stricter system, you might loop and find if ANY match the expected NIM.
    # Here we just output all recognized names separated by comma.
    recognized_nims = []
    
    for face_encoding in face_encodings:
        matches = face_recognition.compare_faces(known_encodings, face_encoding, tolerance=TOLERANCE)
        face_distances = face_recognition.face_distance(known_encodings, face_encoding)
        
        if len(face_distances) > 0:
            best_match_index = face_distances.argmin()
            if matches[best_match_index]:
                recognized_nims.append(known_names[best_match_index])

    if recognized_nims:
        print(",".join(recognized_nims))
    else:
        print("UNKNOWN")

if __name__ == '__main__':
    main()
