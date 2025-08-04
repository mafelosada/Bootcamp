function generarCampos(cantidad) {
  // Ocultar selección de botones
  document.getElementById('contenedor-botones').style.display = 'none';

  // Mostrar formulario
  const contenedorFormulario = document.getElementById('formulario-jugadores');
  contenedorFormulario.style.display = 'block';

  // Cambiar el título
  const titulo = document.getElementById('titulo-jugadores');
  titulo.textContent = `Ingrese los nombres de ${cantidad} jugadores`;

  // Contenedor donde se agregarán los inputs
  const inputsContainer = document.getElementById('inputs-container');
  inputsContainer.innerHTML = ''; // Limpiar antes

  const template = document.getElementById('input-template');

  for (let i = 1; i <= cantidad; i++) {
    const clone = template.content.cloneNode(true);

    // Cambiar texto y name del input
    clone.querySelector('.num').textContent = i;
    const input = clone.querySelector('input');
    input.name = `jugador${i}`;
    input.id = `jugador${i}`;

    inputsContainer.appendChild(clone);
  }
}
