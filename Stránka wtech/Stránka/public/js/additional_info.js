// const additionalInfo = document.getElementById('additionalInfo');
// const additionalInfoButton = document.getElementById('additionalInfoButton');
// const additionalInfoCloseButton = document.getElementById('additionalInfoCloseButton');

// additionalInfoButton.addEventListener('click', function(){
//     additionalInfo.classList.toggle('hidden');
//     additionalInfoCloseButton.classList.toggle('hidden');
//     additionalInfoButton.classList.toggle('hidden');
// })

// additionalInfoCloseButton.addEventListener('click', function(){
//     additionalInfo.classList.toggle('hidden');
//     additionalInfoCloseButton.classList.toggle('hidden');
//     additionalInfoButton.classList.toggle('hidden');
// })

const regex2 = /^additionalInfoButton(\d+)$/;
const regex3 = /^additionalInfoCloseButton(\d+)$/;

const allElements = document.body.getElementsByTagName("*");

const matchedElements2 = Array.from(allElements).filter(element => regex2.test(element.id));
const matchedElements3 = Array.from(allElements).filter(element => regex3.test(element.id));

matchedElements2.forEach((element,index) => {
    element.addEventListener("click", () => {
        const id = element.id.match(regex2)[1];
        const additionalInfo = document.getElementById(`additionalInfo${id}`);
        const additionalInfoCloseButton = document.getElementById(`additionalInfoCloseButton${id}`);

        additionalInfo.classList.toggle('hidden');
        additionalInfoCloseButton.classList.toggle('hidden');
        element.classList.toggle('hidden');
    })
});

matchedElements3.forEach((element,index) => {
    element.addEventListener("click", () => {
        const id = element.id.match(regex3)[1];
        const additionalInfo = document.getElementById(`additionalInfo${id}`);
        const additionalInfoButton = document.getElementById(`additionalInfoButton${id}`);  

        additionalInfo.classList.toggle('hidden');
        element.classList.toggle('hidden');
        additionalInfoButton.classList.toggle('hidden');
    });
});