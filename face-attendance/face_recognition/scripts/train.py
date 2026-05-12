import os
import pickle
import face_recognition

BASE_DIR = os.path.abspath(os.path.join(os.path.dirname(__file__), '..'))
DATASET_DIR = os.path.join(BASE_DIR, 'dataset')
TRAINER_DIR = os.path.join(BASE_DIR, 'trainer')
ENCODINGS_PATH = os.path.join(TRAINER_DIR, 'encodings.pickle')


def ensure_trainer_dir():
    os.makedirs(TRAINER_DIR, exist_ok=True)


def main():
    ensure_trainer_dir()
    known_encodings = []
    known_names = []

    if not os.path.exists(DATASET_DIR):
        print('Folder dataset tidak ditemukan. Pastikan dataset sudah diambil.')
        return

    for nim in os.listdir(DATASET_DIR):
        mahasiswa_path = os.path.join(DATASET_DIR, nim)
        if not os.path.isdir(mahasiswa_path):
            continue

        for image_name in os.listdir(mahasiswa_path):
            if not image_name.lower().endswith(('.jpg', '.jpeg', '.png')):
                continue

            image_path = os.path.join(mahasiswa_path, image_name)
            image = face_recognition.load_image_file(image_path)
            encodings = face_recognition.face_encodings(image)
            if len(encodings) > 0:
                known_encodings.append(encodings[0])
                known_names.append(nim)
            else:
                print(f'Peringatan: wajah tidak terdeteksi pada {image_path}')

    if len(known_encodings) == 0:
        print('Tidak ada encoding wajah. Pastikan dataset valid.')
        return

    with open(ENCODINGS_PATH, 'wb') as f:
        pickle.dump({'encodings': known_encodings, 'names': known_names}, f)

    print(f'Training selesai. Encodings tersimpan di {ENCODINGS_PATH}')


if __name__ == '__main__':
    main()
