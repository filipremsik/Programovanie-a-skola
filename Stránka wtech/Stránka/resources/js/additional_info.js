const additionalInfo = document.getElementById('additionalInfo');
const additionalInfoButton = document.getElementById('additionalInfoButton');
const additionalInfoCloseButton = document.getElementById('additionalInfoCloseButton');

additionalInfoButton.addEventListener('click', function(){
    additionalInfo.classList.toggle('hidden');
    additionalInfoCloseButton.classList.toggle('hidden');
    additionalInfoButton.classList.toggle('hidden');
})

additionalInfoCloseButton.addEventListener('click', function(){
    additionalInfo.classList.toggle('hidden');
    additionalInfoCloseButton.classList.toggle('hidden');
    additionalInfoButton.classList.toggle('hidden');
})