function toggleMediaUpload() {
    var postType = document.getElementById('typep').value;
    var mediaUpload = document.getElementById('media-upload');
    if (postType === 'MEDIA') {
        mediaUpload.style.display = 'block';
    } else {
        mediaUpload.style.display = 'none';
    }
}

function validateFileCount() {
    const mediaInput = document.getElementById('media');
    const maxFiles = 5;
    const maxSize = 1024 * 1024; // 1MB in bytes
    const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/svg+xml'];
    const allowedRatios = [
        { ratio: 1, tolerance: 0.2, display: '1:1' },
        { ratio: 4 / 3, tolerance: 0.2, display: '4:3' },
        { ratio: 16 / 9, tolerance: 0.2, display: '16:9' }
    ];

    let valid = true;
    let errorMessage = '';
    const files = Array.from(mediaInput.files);
    if (files.length > maxFiles) {
        errorMessage += `You can only upload a maximum of ${maxFiles} files.\n`;
        valid = false;
    }

    const dataTransfer = new DataTransfer();

    const promises = files.slice(0, maxFiles).map((file, index) => {
        return new Promise(resolve => {
            // Validate file type
            if (!allowedTypes.includes(file.type)) {
                errorMessage += `File type not allowed: ${file.name}. Allowed types: ${allowedTypes.join(', ')}\n`;
                valid = false;
                resolve(false);
                return;
            }

            // Validate file size
            if (file.size > maxSize) {
                errorMessage += `File size too large: ${file.name}. Maximum size: ${(maxSize / 1024 / 1024).toFixed(1)}MB\n`;
                valid = false;
                resolve(false);
                return;
            }

            // Validate aspect ratio
            const img = new Image();
            img.onload = function () {
                const aspectRatio = img.width / img.height;
                const isValidRatio = allowedRatios.some(({ ratio, tolerance }) =>
                    Math.abs(aspectRatio - ratio) <= tolerance
                );
                if (!isValidRatio) {
                    errorMessage += `Invalid aspect ratio for file: ${file.name}. Allowed ratios: ${allowedRatios.map(({ display }) => display).join(', ')}\n`;
                    valid = false;
                    resolve(false);
                } else {
                    resolve(true);
                }
            };
            img.onerror = () => resolve(false); // Handle invalid image files
            img.src = URL.createObjectURL(file);
        }).then(isValid => {
            if (isValid) {
                dataTransfer.items.add(file);
            }
        });
    });

    Promise.all(promises).then(() => {
        if (!valid) {
            alert(errorMessage);
        }

        // Show previews for valid files
        const mediaContainer = document.getElementById('media-preview');
        mediaContainer.innerHTML = ''; // Clear previous previews

        Array.from(dataTransfer.files).forEach((file, index) => {
            if (allowedTypes.includes(file.type)) {
                const wrapper = document.createElement('div');
                wrapper.classList.add('image-wrapper');

                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);

                const label = document.createElement('span');
                label.classList.add('image-label');
                label.textContent = `Image ${index + 1}`;

                wrapper.appendChild(img);
                wrapper.appendChild(label);
                mediaContainer.appendChild(wrapper);
            }
        });

        mediaInput.files = dataTransfer.files; // Update input with valid files only
    });
}
