function send() {
    const texts = document.querySelectorAll("div textarea");
    const result = document.querySelector("textarea[readonly]");
    const string = document.getElementById("string").value;
    let str = "";

    texts.forEach(text => str += text.value + "@separatorphp@");

    const textEncoder = new TextEncoder();
    const utf8Bytes = textEncoder.encode(str);
    const file = new Blob([utf8Bytes], { type: 'text/plain;charset=utf-8' });
    const formData = new FormData();
    formData.append('file', file);

    formData.append('string', string);
    fetch('../php/scripts.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data =>
            result.value = data.result
        )
        .catch(error => {
            console.error('Erro:', error);
        });
}

function newGroup() {
    const container = document.getElementsByClassName("div-textareas")[0];
    const counter = container.children.length + 1;
    const group = {
        fieldset: document.createElement("fieldset"),
        legend: document.createElement("legend"),
        divTextarea: document.createElement("div"),
        textarea: document.createElement("textarea"),
        divButton: document.createElement("div"),
        less: document.createElement("button"),
        more: document.createElement("button"),
    };

    group.legend.textContent = "Grupo " + counter;
    group.legend.contentEditable = "true";
    group.textarea.placeholder = "Insira seu texto que representa um grupo aqui...";
    group.less.textContent = "-";
    group.more.textContent = "+";

    container.appendChild(group.fieldset);

    group.fieldset.appendChild(group.legend);
    group.fieldset.appendChild(group.divTextarea);
    group.fieldset.appendChild(group.divButton);

    group.divTextarea.appendChild(group.textarea);

    group.divButton.appendChild(group.less);
    group.divButton.appendChild(group.more);
}

function removeGroup() {
    const container = document.getElementsByClassName("div-textareas")[0];

    if (container.children.length >= 3)
        container.children[container.children.length - 1].remove();
}

function newTextArea() {
    const textarea = document.createElement("textarea");
    const container = document.querySelector("div");

    textarea.classList.add("optional");
    textarea.placeholder = "Insira seu texto aqui...";
    container.appendChild(textarea);
}

function removeTextArea() {
    const textarea = document.getElementsByClassName("optional");

    if (textarea.length > 0)
        textarea[textarea.length - 1].remove();
}

function range(id) {
    const coherence = document.getElementById("coherence");
    const creative = document.getElementById("creative");

    switch (id) {
        case "coherence":
            creative.value = 10 - coherence.value;
            break;
        case "creative":
            coherence.value = 10 - creative.value;
            break;
    }
}