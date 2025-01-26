from flask import Flask, Response, send_file
import time

app = Flask(__name__)

FILE_PATH = "data/largefile.csv"

@app.route('/download')
def download():
    def generate():
        with open(FILE_PATH, 'rb') as f:
            while chunk := f.read(1024 * 1024):  # 1 MB chunks (1024 * 1024 bytes)
                yield chunk
                time.sleep(1)  # Speed limit
    return Response(generate(), mimetype='application/octet-stream')

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)
