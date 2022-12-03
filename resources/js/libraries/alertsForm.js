// Libreria para manejar mensajes de alertas en un formulario
// --------------------------------------------------------------------------

// Nodos requeridos para ejecutar el manejador de alertas en el formulario 
const DOMstrings = {
    alertsContainer: document.getElementById('f_alertsContainer'),
    alertsLoader: document.getElementById('f_alertsLoader'),
    alertsUl: document.getElementById('f_alertsUl'),
    alertsDismiss: document.getElementById('f_alertsDismiss')
};


/**
 * Evento para ocultar el contenedor de alertas
 */
DOMstrings.alertsDismiss.addEventListener('click', function(){
    resetAlertContainer();
    DOMstrings.alertsContainer.classList.add('d-none');
});

/**
 * Función para resetear estilos y mensajes en la caja de alertas
 */
export function resetAlertContainer(){
    DOMstrings.alertsContainer.classList.remove('alert-success');
    DOMstrings.alertsContainer.classList.remove('alert-danger');
    DOMstrings.alertsUl.textContent = '';
    DOMstrings.alertsDismiss.classList.add('d-none');
    DOMstrings.alertsContainer.classList.add('d-none');
}

/**
 * Función para mostrar la animación cargando
 */
export function addEffectLoading(){
    DOMstrings.alertsLoader.classList.remove('d-none');
    DOMstrings.alertsDismiss.classList.add('d-none');
    DOMstrings.alertsContainer.classList.remove('d-none');
}

/**
 * Función para ocultar la animación cargando
 */
export function removeEffectLoading(){
    DOMstrings.alertsLoader.classList.add('d-none');
    DOMstrings.alertsDismiss.classList.remove('d-none');
    DOMstrings.alertsContainer.classList.add('d-none');
}

/**
 * Función para agregar contenido al 'ul' de validación
 * @param {array} mensajes
 */
export function agregarMensajesValidacion(mensajes){
    DOMstrings.alertsContainer.classList.remove('d-none');
    mensajes.forEach(element => {
        let li = document.createElement('li');
        li.textContent = element;
        DOMstrings.alertsUl.appendChild(li);
    });
}

/**
 * Función para agregar contenido al 'ul' de validación
 */
export function alertsError(){
    DOMstrings.alertsContainer.classList.add('alert-danger');
}

/**
 * Función para agregar contenido al 'ul' de validación
 */
export function alertsSuccess(){
    DOMstrings.alertsContainer.classList.add('alert-success');
}