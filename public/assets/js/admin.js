document.addEventListener('DOMContentLoaded', () => {
  const scope = document.getElementById('scope_type');
  if (scope) {
    const sync = () => {
      const value = scope.value;
      document.querySelectorAll('[data-scope]').forEach((el) => {
        el.style.display = el.getAttribute('data-scope') === value ? '' : 'none';
      });
    };
    scope.addEventListener('change', sync);
    sync();
  }

  const input = document.querySelector('[data-image-input]');
  const preview = document.querySelector('[data-image-preview]');
  const hint = document.querySelector('[data-dropzone-hint]');
  const dropzone = document.querySelector('[data-dropzone]');
  if (!input || !preview) {
    return;
  }

  const showFile = (file) => {
    if (!file || !file.type.startsWith('image/')) {
      return;
    }
    preview.src = URL.createObjectURL(file);
    preview.hidden = false;
    if (hint) {
      hint.textContent = file.name;
    }
  };

  input.addEventListener('change', () => {
    showFile(input.files && input.files[0]);
  });

  if (!dropzone) {
    return;
  }

  ['dragenter', 'dragover'].forEach((eventName) => {
    dropzone.addEventListener(eventName, (event) => {
      event.preventDefault();
      dropzone.classList.add('is-dragover');
    });
  });
  ['dragleave', 'drop'].forEach((eventName) => {
    dropzone.addEventListener(eventName, (event) => {
      event.preventDefault();
      dropzone.classList.remove('is-dragover');
    });
  });
  dropzone.addEventListener('drop', (event) => {
    const file = event.dataTransfer && event.dataTransfer.files[0];
    if (!file) {
      return;
    }
    const transfer = new DataTransfer();
    transfer.items.add(file);
    input.files = transfer.files;
    showFile(file);
  });
});
