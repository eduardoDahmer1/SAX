var iconPerson = document.querySelector('.bi-person-fill')
var iconTruck = document.querySelector('.bi-truck')
var iconCard = document.querySelector('.bi-credit-card')
document.addEventListener('DOMContentLoaded', function () {
  const steps = document.querySelectorAll('.step');
  let currentStep = 0;

  function showStep(stepIndex) {
    steps.forEach((step, index) => {
      step.classList.toggle('active', index === stepIndex);
    });
  }

  function nextStep() {
    currentStep += 1;
    if (currentStep < steps.length) {
      if(currentStep == 1) {
        iconPerson.classList.add('color-2')
        iconPerson.parentNode.previousElementSibling.classList.add('bg-color-2')
      }
      if (currentStep == 2) {
        iconTruck.classList.add('color-2')
        iconTruck.parentNode.previousElementSibling.classList.add('bg-color-2')
      }
      if (currentStep == 3) {
        iconCard.classList.add('color-2')
        iconCard.parentNode.previousElementSibling.classList.add('bg-color-2')
      }
      showStep(currentStep);
    } else {
      alert('Você atingiu o último passo!');
      currentStep = steps.length - 1;
    }
  }

  function prevStep() {
    currentStep -= 1;
    if (currentStep == 2) {
      iconCard.classList.remove('color-2')
      iconCard.parentNode.previousElementSibling.classList.remove('bg-color-2')
    }
    if (currentStep == 1) {
      iconTruck.classList.remove('color-2')
      iconTruck.parentNode.previousElementSibling.classList.remove('bg-color-2')
    }
    if (currentStep == 0) {
      iconPerson.classList.remove('color-2')
      iconPerson.parentNode.previousElementSibling.classList.remove('bg-color-2')
    }
    if (currentStep >= 0) {
      showStep(currentStep);
    } else {
      currentStep = 0;
    }
  }

  var btnContinue = document.querySelectorAll('.btn-continue')
  var btnPrev = document.querySelectorAll('.btn-back')

  btnContinue.forEach(btn => {
    btn.addEventListener('click', nextStep);
  })

  btnPrev.forEach(btn => {
    btn.addEventListener('click', prevStep);
  })

  showStep(currentStep);
  });

  var shippingType = document.querySelectorAll('input[name="shipping"]')
  var newAddress = document.querySelector('.new-address')
  var selectLocal = document.querySelector('.select-local')
  var cdeMap = document.querySelector('.CDE-MAP')
  var asuncion = document.querySelector('.ASUNCION-MAP')
  shippingType.forEach(input => {
    input.addEventListener('change', () => {
      if(input.value == 2) {
        newAddress.classList.replace('d-none', 'test')
      } else {
        newAddress.classList.replace('test', 'd-none')
      }
      if(input.value == 3) {
        selectLocal.classList.replace('d-none', 'd-flex')
        cdeMap.classList.replace('d-none', 'd-flex')
      } else {
        selectLocal.classList.replace('d-flex', 'd-none')
        selectLocal.value = 1
        cdeMap.classList.replace('d-flex', 'd-none')
        asuncion.classList.replace('d-flex', 'd-none')
      }
    })
  })
  selectLocal.addEventListener('change', () => {
    if(selectLocal.value == 1) {
      cdeMap.classList.replace('d-none', 'd-flex')
      asuncion.classList.replace('d-flex', 'd-none')
    } else {
      cdeMap.classList.replace('d-flex', 'd-none')
      asuncion.classList.replace('d-none', 'd-flex')
    }
  })
  var payType = document.querySelectorAll('input[name="pay-method"]')
  payType.forEach(type => {
    type.addEventListener('change', () => {
      if(type.value == 1) {
        type.nextElementSibling.classList.add('color-2')
        type.nextElementSibling.querySelector('i').classList.add('color-2')
        type.parentNode.classList.add('color-2')

        payType[1].nextElementSibling.querySelector('i').classList.remove('color-2')
        payType[1].nextElementSibling.classList.remove('color-2')
        payType[1].parentNode.classList.remove('color-2')
        payType[2].nextElementSibling.querySelector('i').classList.remove('color-2')
        payType[2].nextElementSibling.classList.remove('color-2')
        payType[2].parentNode.classList.remove('color-2')
      }
      if(type.value == 2) {
        type.nextElementSibling.classList.add('color-2')
        type.nextElementSibling.querySelector('i').classList.add('color-2')
        type.parentNode.classList.add('color-2')

        payType[2].nextElementSibling.querySelector('i').classList.remove('color-2')
        payType[2].nextElementSibling.classList.remove('color-2')
        payType[2].parentNode.classList.remove('color-2')
        payType[0].nextElementSibling.querySelector('i').classList.remove('color-2')
        payType[0].nextElementSibling.classList.remove('color-2')
        payType[0].parentNode.classList.remove('color-2')
      }
      if(type.value == 3) {
        type.nextElementSibling.classList.add('color-2')
        type.nextElementSibling.querySelector('i').classList.add('color-2')
        type.parentNode.classList.add('color-2')

        payType[1].nextElementSibling.querySelector('i').classList.remove('color-2')
        payType[1].nextElementSibling.classList.remove('color-2')
        payType[1].parentNode.classList.remove('color-2')
        payType[0].nextElementSibling.querySelector('i').classList.remove('color-2')
        payType[0].nextElementSibling.classList.remove('color-2')
        payType[0].parentNode.classList.remove('color-2')

      }
    })
  })