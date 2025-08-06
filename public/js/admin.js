document.addEventListener('DOMContentLoaded', function () {
    const search_property = document.getElementById('property-search');

    if (search_property) {
        search_property.addEventListener('keydown', function (event) {
            if (event.key === 'Enter') {
                const uniqueId = search_property.value.trim();
                if (uniqueId !== '') {
                    window.location.href = `/admin/find/${encodeURIComponent(uniqueId)}`;
                }
            }
        });
    }



    const multipleImages = document.getElementById('input_images');
    const preview = document.getElementById('image_preview');

    if (multipleImages && preview) {
        let selectedFiles = [];

        multipleImages.addEventListener('change', function () {
            selectedFiles = Array.from(multipleImages.files);
            renderPreviews();
        });

        function renderPreviews() {
            preview.innerHTML = '';
            selectedFiles.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const wrapper = document.createElement('div');
                    wrapper.style.position = 'relative';

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.maxWidth = '150px';
                    img.style.maxHeight = '150px';
                    img.style.border = '1px solid #ccc';
                    img.style.padding = '4px';

                    const delBtn = document.createElement('button');
                    delBtn.innerHTML = '×';
                    delBtn.style.position = 'absolute';
                    delBtn.style.top = '0';
                    delBtn.style.right = '0';
                    delBtn.style.background = 'red';
                    delBtn.style.color = 'white';
                    delBtn.style.border = 'none';
                    delBtn.style.cursor = 'pointer';

                    delBtn.addEventListener('click', () => {
                        selectedFiles.splice(index, 1);
                        updateFileInput();
                        renderPreviews();
                    });

                    wrapper.appendChild(img);
                    wrapper.appendChild(delBtn);
                    preview.appendChild(wrapper);
                };
                reader.readAsDataURL(file);
            });
        }

        function updateFileInput() {
            const dataTransfer = new DataTransfer();
            selectedFiles.forEach(file => dataTransfer.items.add(file));
            multipleImages.files = dataTransfer.files;
        }
    }

    const previewContainer = document.getElementById('image_preview'); // your preview div
    const fileButtons = document.querySelectorAll('.file-clear-button[data-filename]');
    const singleFileButtons = document.querySelectorAll('.file_clear_button[data-filename]');
    fileButtons.forEach(button => {
        const filePath = button.getAttribute('data-filename');
        if (filePath && previewContainer) {
            const wrapper = document.createElement('div');
            wrapper.style.position = 'relative';

            const img = document.createElement('img');
            img.src = '/storage/uploads/' + filePath.split('_')[0] + "/" + filePath; // adjust this path if needed
            img.style.maxWidth = '150px';
            img.style.maxHeight = '150px';
            img.style.border = '1px solid #ccc';
            img.style.padding = '4px';

            const delBtn = document.createElement('button');
            delBtn.type = 'button'; // ✅ Prevent form submission
            delBtn.innerHTML = '×';
            delBtn.style.position = 'absolute';
            delBtn.style.top = '0';
            delBtn.style.right = '0';
            delBtn.style.background = 'red';
            delBtn.style.color = 'white';
            delBtn.style.border = 'none';
            delBtn.style.cursor = 'pointer';

            delBtn.addEventListener('click', () => {
                // Find and click the original file-clear-button
                const originalClearButton = document.querySelector(
                    `.file-clear-button[data-filename="${CSS.escape(filePath)}"]`
                );
                if (originalClearButton) {
                    originalClearButton.click();
                }
                wrapper.remove();
            });

            wrapper.appendChild(img);
            wrapper.appendChild(delBtn);
            previewContainer.appendChild(wrapper);
        }
    });

    singleFileButtons.forEach(button => {
        const filePath = button.getAttribute('data-filename');
        if (filePath && previewContainer) {
            const wrapper = document.createElement('div');
            wrapper.style.position = 'relative';

            const img = document.createElement('img');
            img.src = '/storage/uploads/' + filePath; // adjust this path if needed
            img.style.maxWidth = '150px';
            img.style.maxHeight = '150px';
            img.style.border = '1px solid #ccc';
            img.style.padding = '4px';

            const delBtn = document.createElement('button');
            delBtn.type = 'button'; // ✅ Prevent form submission
            delBtn.innerHTML = '×';
            delBtn.style.position = 'absolute';
            delBtn.style.top = '0';
            delBtn.style.right = '0';
            delBtn.style.background = 'red';
            delBtn.style.color = 'white';
            delBtn.style.border = 'none';
            delBtn.style.cursor = 'pointer';

            delBtn.addEventListener('click', () => {
                // Find and click the original file-clear-button
                const originalClearButton = document.querySelector(
                    `.file_clear_button[data-filename="${CSS.escape(filePath)}"]`
                );
                if (originalClearButton) {
                    originalClearButton.click();
                }
                wrapper.remove();
            });

            wrapper.appendChild(img);
            wrapper.appendChild(delBtn);
            previewContainer.appendChild(wrapper);
        }
    });

});
