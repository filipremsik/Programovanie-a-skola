const fileInput = document.getElementById('fileInput');
const imageContainer = document.getElementById('imagePreview');
const photosHolder = document.getElementById('photosHolder');

var counter=0;




fileInput.addEventListener('change', function(event) {
        console.log(counter)
        const files = this.files;
  
        for (let i = 0; i < files.length; i++) {
      const file = files[i];
      
      
      if (file && counter<4) {
          const reader = new FileReader();
          
          photosHolder.classList.replace('row-span-4','row-span-3')
          
          reader.onload = function(event) {
            counter++;

              const imageUrl = event.target.result;
              const imageElement = document.createElement('img');
              imageElement.classList.add('scale-100','max-h-fit','rounded-lg')
              imageElement.src = imageUrl;
              imageContainer.appendChild(imageElement);
            };
            
            reader.readAsDataURL(file);
        }
    }
});
