document.addEventListener('DOMContentLoaded', () => {
  const scope = document.getElementById('scope_type');
  if (!scope) return;

  const sync = () => {
    const value = scope.value;
    document.querySelectorAll('[data-scope]').forEach((el) => {
      el.style.display = el.getAttribute('data-scope') === value ? '' : 'none';
    });
  };

  scope.addEventListener('change', sync);
  sync();
});
