function openModal(id) {
  const modal = document.getElementById(id);
  if (modal) modal.classList.add('open');
}
function closeModal(id) {
  const modal = document.getElementById(id);
  if (modal) modal.classList.remove('open');
}
document.querySelectorAll('.modal').forEach((modal) =>
  modal.addEventListener('click', (event) => {
    if (event.target === modal) modal.classList.remove('open');
  }),
);

const pageLoader = document.querySelector('.page-loader');
document.querySelectorAll('a[href]').forEach((link) => {
  link.addEventListener('click', (event) => {
    const target = new URL(link.href, window.location.href);
    if (target.origin !== window.location.origin || link.target === '_blank')
      return;
    event.preventDefault();
    pageLoader?.classList.add('visible');
    window.setTimeout(() => {
      window.location.href = target.href;
    }, 220);
  });
});

window.addEventListener('pageshow', () =>
  pageLoader?.classList.remove('visible'),
);
