const fileInput = document.getElementById('fileInput');
const imageContainer = document.getElementById('imagePreview');
const photosHolder = document.getElementById('photosHolder');
const parametersHolder = document.getElementById('parametersHolder');
const add_parameter = document.getElementById('add-parameter');

const number_of_images = document.getElementsByClassName('product_image').length;

var allElements = document.querySelectorAll('*');
const regex1 = /^delete-parameter-(\d+)$/;
const regex2 = /^image_id_img-(\d+)$/;

var counter=number_of_images;
var fileList = [];

var new_parameters=deleteParameters(allElements,regex1);
deletePreLoadedImg(allElements,regex2);

fileInput.addEventListener('change', function(event) {
  if(counter<2){
      const files = this.files;
  
      for (let i = 0; i < files.length; i++) {
      const file = files[i];
      
      if (file && counter<2) {
          const reader = new FileReader();
          
          photosHolder.classList.replace('row-span-4','row-span-3')
          
          reader.onload = function(event) {
            counter++;

              const imageUrl = event.target.result;
              const imageElement = document.createElement('img');
              imageElement.classList.add('scale-100','max-h-fit','rounded-lg','product_image','cursor-pointer')
              imageElement.src = imageUrl;
              imageContainer.appendChild(imageElement);

              imageElement.addEventListener('click', function() {
                fileInput.value=null;
                imageElement.remove();

                counter--;
              });
          
            };
            
            reader.readAsDataURL(file);
        }
    }
    console.log(files);
  }
});

add_parameter.addEventListener('click', function(event) {

  const new_parameter_key = document.createElement('input');
  const new_parameter_value = document.createElement('input');
  const new_parameter_div = document.createElement('div');
  const new_parameter_delete_button = document.createElement('button');
  
  new_parameter_key.classList.add('w-full','h-8','rounded','text-dark-purple','font-semibold','p-2','mt-1','mb-2');
  new_parameter_value.classList.add('w-full','h-8','rounded','text-dark-purple','font-semibold','p-2','mt-1','mb-2');
  new_parameter_div.classList.add('flex','w-fit','justify-center','items-center');
  new_parameter_delete_button.classList.add('flex','justify-center','items-center','min-w-4','max-h-4','rounded-full' ,'font-bold','bg-white','text-dark-purple','hover:text-white','hover:bg-dark-purple','hover:border-2','hover:border-white')  

  new_parameter_delete_button.innerHTML = '-';

  new_parameter_key.setAttribute('placeholder','Key');
  new_parameter_value.setAttribute('placeholder','Value');

  new_parameter_key.setAttribute('name','parameter-key-'+new_parameters);
  new_parameter_value.setAttribute('name','parameter-value-'+new_parameters);

  new_parameter_delete_button.setAttribute('id','delete-parameter-'+new_parameters);
  new_parameter_delete_button.setAttribute('type','button');
  
  parametersHolder.appendChild(new_parameter_key);
  parametersHolder.appendChild(new_parameter_value);
  parametersHolder.appendChild(new_parameter_div);
  
  new_parameter_div.appendChild(new_parameter_delete_button);

  parametersHolder.scrollTo(0,parametersHolder.scrollHeight);

  new_parameters=deleteParameters(allElements,regex1);

});


function deleteParameters(allElements,regex1){
  allElements= document.querySelectorAll('*');
  var matchedElements1 = Array.from(allElements).filter(element => regex1.test(element.id));

  console.log(matchedElements1.length)

  matchedElements1.forEach(element => {
      element.addEventListener('click', function() {
          const id = element.id.match(regex1)[1];
          const key = document.querySelector(`input[name="parameter-key-${id}"]`);
          const value = document.querySelector(`input[name="parameter-value-${id}"]`);
          const div = document.getElementById(`delete-parameter-${id}`).parentElement;
  
          key.remove();
          value.remove();
          element.remove();
          div.remove();

          new_parameters=deleteParameters(allElements,regex1);

      });
  });

  return matchedElements1.length;
}

function deletePreLoadedImg(allElements,regex2){
  var matchedElements2 = Array.from(allElements).filter(element => regex2.test(element.id));

  
  matchedElements2.forEach(element => {
    element.addEventListener('click', function() {
          console.log('Preloaded-image-listener',matchedElements2.length)

          const id = element.id.match(regex2)[1];
          const img = document.getElementById(`image_id_img-${id}`);
          const input = document.getElementById(`image_id_input-${id}`).parentElement;
  
          img.remove();
          input.remove();

          new_parameters=deletePreLoadedImg(allElements,regex2);

      });
  });

  return matchedElements2.length;
}
