const textarea = document.querySelector("#editor-{{ $getId() }}");
const editorData = editor.getData();

// Update textarea dengan nilai terbaru
textarea.innerHTML = editorData;
console.log(textarea);
