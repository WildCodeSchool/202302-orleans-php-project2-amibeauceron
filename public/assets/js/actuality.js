document.getElementById('files').addEventListener('change', function (e) {
    if (e.target.files[0]) {
        const reader = new FileReader()
        let files = document.getElementById('files').files
        reader.onload = async (event) => {
            document.getElementById('preview').setAttribute('src', event.target.result)
        }
        reader.readAsDataURL(files[0])
    }
});