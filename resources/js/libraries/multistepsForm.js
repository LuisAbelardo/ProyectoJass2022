// Libreria para hacer un formulario por pasos
// Documentatión: https://github.com/nat-davydova/multisteps-form
// 
// Una funcion modificada => setFormHeight, 
// eventos modificado => 'PREV/NEXT BTNS CLICK'
// eventos borrados => 'STEPS BAR CLICK FUNCTION', load, resize
// una función quitada => setAnimationType
// 
// Para usar la libreria importar nextPanel
// ------------------------------------------------------------------------------------------------


// Elementos para el 'form wizard' (DOM elements)
const DOMstrings = {
  stepsForm: document.querySelector('.multisteps-form__form'),
  stepsBar: document.querySelector('.multisteps-form__progress'),
  stepsBtnClass: 'multisteps-form__progress-btn',
  stepsBtns: document.querySelectorAll(`.multisteps-form__progress-btn`),
  stepFormPanelClass: 'multisteps-form__panel',
  stepFormPanels: document.querySelectorAll('.multisteps-form__panel'),
  stepPrevBtnClass: 'js-btn-prev',
  stepNextBtnClass: 'js-btn-next' 
};
  
//remove class from a set of items
const removeClasses = (elemSet, className) => {

  elemSet.forEach(elem => {

    elem.classList.remove(className);

  });

};
  
//return exect parent node of the element
const findParent = (elem, parentClass) => {

  let currentNode = elem;

  while (!currentNode.classList.contains(parentClass)) {
    currentNode = currentNode.parentNode;
  }

  return currentNode;

};


//set all steps before clicked (and clicked too) to active
const setActiveStep = activeStepNum => {

  //remove active state from all the state
  removeClasses(DOMstrings.stepsBtns, 'js-active');

  //set picked items to active
  DOMstrings.stepsBtns.forEach((elem, index) => {

    if (index <= activeStepNum) {
      elem.classList.add('js-active');
    }

  });
};

//get active panel
const getActivePanel = () => {

  let activePanel;

  DOMstrings.stepFormPanels.forEach(elem => {

    if (elem.classList.contains('js-active')) {

      activePanel = elem;

    }

  });

  return activePanel;

};

//open active panel (and close unactive panels)
const setActivePanel = activePanelNum => {

  //remove active class from all the panels
  removeClasses(DOMstrings.stepFormPanels, 'js-active');

  //show active panel
  DOMstrings.stepFormPanels.forEach((elem, index) => {
    if (index === activePanelNum) {

      elem.classList.add('js-active');

      setFormHeight(elem);

    }
  });

};

//set form height equal to current panel height
const formHeight = activePanel => {

  const activePanelHeight = activePanel.offsetHeight;
  DOMstrings.stepsForm.style.height = `${activePanelHeight}px`

};


// Función modificada:
// Se agrego el condicional 'if' para evitar manipular elementos nulos o no nulos pero vacios
const setFormHeight = () => {

  if(DOMstrings.stepFormPanels !== null && DOMstrings.stepFormPanels.lenght > 0)
  {
    const activePanel = getActivePanel();
    formHeight(activePanel);
  }

};


//NEXT BTNS CLICK
export function nextPanel(e){

  const eventTarget = e.target;

  //check if we clicked on `PREV` or NEXT` buttons
  if (!(eventTarget.classList.contains(`${DOMstrings.stepNextBtnClass}`)))
  {
    return;
  }

  //find active panel
  const activePanel = findParent(eventTarget, `${DOMstrings.stepFormPanelClass}`);

  let activePanelNum = Array.from(DOMstrings.stepFormPanels).indexOf(activePanel);

  activePanelNum++;
  setActiveStep(activePanelNum);
  setActivePanel(activePanelNum);

}

//PREV BTNS CLICK
DOMstrings.stepsForm.addEventListener('click', e => {

  const eventTarget = e.target;

  //check if we clicked on `PREV` button
  if (!(eventTarget.classList.contains(`${DOMstrings.stepPrevBtnClass}`)))
  {
    return;
  }

  //find active panel
  const activePanel = findParent(eventTarget, `${DOMstrings.stepFormPanelClass}`);

  let activePanelNum = Array.from(DOMstrings.stepFormPanels).indexOf(activePanel);

  activePanelNum--;
  setActiveStep(activePanelNum);
  setActivePanel(activePanelNum);
  
});