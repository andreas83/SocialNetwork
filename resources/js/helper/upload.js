export function upload(callback){

  var element = document.createElement('div');
  element.innerHTML = '<input  type="file">';
  let fileInput = element.firstChild;
  let token=localStorage.getItem('token');
  fileInput.addEventListener('change', function() {

    let formData = new FormData();

    for (let i = 0; i < fileInput.files.length; i++) {
        let file = fileInput.files[i];
        formData.append('upload[]', file, file.name);
    }
    var xhr = new XMLHttpRequest();

    

    xhr.open('POST', '/api/content/upload', true);
    xhr.setRequestHeader('Authorization', 'Bearer ' + token);
    xhr.onload = function () {
        if (xhr.status === 200) {

          const result = JSON.parse(xhr.responseText);

          return callback(result.path[0]);

        } else {
            //vm.error = xhr.responseText;
            return callback(xhr.responseText);
        }
    };
    xhr.send(formData);


  });

  fileInput.click();
}
