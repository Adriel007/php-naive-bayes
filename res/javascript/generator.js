function send() {
    const groups = [];

    for (const value of document.querySelectorAll(".div-textareas fieldset")) {
        const json = {
            title: value.children[0].textContent,
            array: []
        };

        for (let c = 0; c < value.children[1].children.length; c++)
            json.array.push(value.children[1].children[c].value);

        groups.push(json);
    }

    const result = document.querySelector("textarea[readonly]");
    const string = document.getElementById("string").value;

    const file = new Blob([JSON.stringify(groups)], { type: 'application/json;charset=utf-8' });
    const formData = new FormData();
    formData.append('file', file);
    formData.append('string', string);
    fetch('../php/scripts.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            data = data.result;
            console.log(data)
            let str = "Seu texto foi classificado na seguinte ordem:\n";
            for (const key in data) {
                console.log(key)
                str += `${key}: ${(data[key] * 100).toFixed(3)}%\n`;
            }
            result.value = str;
        })
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
    group.textarea.placeholder = "Insira um texto para o treinamento...";
    group.less.textContent = "-";
    group.less.onclick = removeTextArea;
    group.less.classList.add("gp-" + counter);
    group.more.textContent = "+";
    group.more.onclick = newTextArea;
    group.more.classList.add("gp-" + counter);

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

function newTextArea(element) {
    if (element?.target)
        element = element.target;

    const counter = element.classList[0].split("-")[1];
    const container = document.querySelectorAll(".div-textareas fieldset")[counter - 1].children[1];
    const textarea = document.createElement("textarea");
    textarea.placeholder = "Insira um texto para o treinamento...";
    container.appendChild(textarea);
}

function removeTextArea(element) {
    if (element?.target)
        element = element.target;

    const counter = element.classList[0].split("-")[1];
    const container = document.querySelectorAll(".div-textareas fieldset")[counter - 1].children[1];

    if (container.children.length >= 2)
        container.children[container.children.length - 1].remove();
}