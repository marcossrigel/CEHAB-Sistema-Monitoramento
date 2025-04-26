function toggleAccordion() {
  const content = document.getElementById('accordion-content');
  const icon = document.getElementById('accordion-icon');

  content.classList.toggle('hidden');

  if (content.classList.contains('hidden')) {
    icon.innerHTML = '⌄'; // seta pra baixo
  }
}
