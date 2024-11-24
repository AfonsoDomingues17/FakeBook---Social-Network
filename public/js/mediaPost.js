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
        { ratio: 1, tolerance: 0.2, display: '1:1' }, // 1:1 with 20% tolerance
        { ratio: 4/3, tolerance: 0.2, display: '4:3' }, // 4:3 with 20% tolerance
        { ratio: 16/9, tolerance: 0.2, display: '16:9' } // 16:9 with 20% tolerance
    ];

    let valid = true;
    let errorMessage = '';

    if (mediaInput.files.length > maxFiles) {
        errorMessage += `You can only upload a maximum of ${maxFiles} files.\n`;
        valid = false;
    }

    const promises = [];

    for (let i = 0; i < mediaInput.files.length; i++) {
        const file = mediaInput.files[i];

        if (!allowedTypes.includes(file.type)) {
            errorMessage += `File type not allowed: ${file.name}. Allowed types: ${allowedTypes.join(', ')}\n`;
            valid = false;
        }

        if (file.size > maxSize) {
            errorMessage += `File size too large: ${file.name}. Maximum size: ${maxSize / 1024 / 1024}MB\n`;
            valid = false;
        }

        // Check aspect ratio
        const img = new Image();
        const promise = new Promise((resolve) => {
            img.onload = function() {
                const aspectRatio = img.width / img.height;
                const isValidRatio = allowedRatios.some(({ ratio, tolerance }) => {
                    return Math.abs(aspectRatio - ratio) <= tolerance;
                });
                if (!isValidRatio) {
                    errorMessage += `Invalid aspect ratio for file: ${file.name}. Allowed ratios: ${allowedRatios.map(({ display }) => display).join(', ')}\n`;
                    valid = false;
                }
                resolve();
            };
        });
        img.src = URL.createObjectURL(file);
        promises.push(promise);
    }

    Promise.all(promises).then(() => {
        if (!valid) {
            alert(errorMessage);

            // Create a DataTransfer object to retain only the valid files
            const dataTransfer = new DataTransfer();
            for (let i = 0; i < mediaInput.files.length; i++) {
                const file = mediaInput.files[i];
                if (allowedTypes.includes(file.type) && file.size <= maxSize) {
                    const img = new Image();
                    const promise = new Promise((resolve) => {
                        img.onload = function() {
                            const aspectRatio = img.width / img.height;
                            const isValidRatio = allowedRatios.some(({ ratio, tolerance }) => {
                                return Math.abs(aspectRatio - ratio) <= tolerance;
                            });
                            if (isValidRatio) {
                                dataTransfer.items.add(file);
                            }
                            resolve();
                        };
                    });
                    img.src = URL.createObjectURL(file);
                    promises.push(promise);
                }
            }

            // Update the input files with the valid files
            Promise.all(promises).then(() => {
                mediaInput.files = dataTransfer.files;
            });
        }
    });
}