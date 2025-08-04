function generarCampos(cantidad) {
  document.getElementById('contenedor-botones').style.display = 'none';

  const contenedorFormulario = document.getElementById('formulario-jugadores');
  contenedorFormulario.style.display = 'block';

  const titulo = document.getElementById('titulo-jugadores');
  titulo.textContent = `Ingrese los nombres de ${cantidad} jugadores`;

  const inputsContainer = document.getElementById('inputs-container');
  inputsContainer.innerHTML = ''; 

  const template = document.getElementById('input-template');

  for (let i = 1; i <= cantidad; i++) {
    const clone = template.content.cloneNode(true);

    clone.querySelector('.num').textContent = i;
    const input = clone.querySelector('input');
    input.name = `jugador${i}`;
    input.id = `jugador${i}`;

    inputsContainer.appendChild(clone);
  }
}
