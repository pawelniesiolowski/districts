document.getElementById('district-form').addEventListener('input', function(e) {
    filter(e);
});

const fields = {
    name: '',
    area: '',
    population: '',
    city: ''
};

function filter(e) {
    const name = e.target.getAttribute('name');
    fields[name] = e.target.value;
    const districtsRequest = JSON.stringify(fields);
    const xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            const table = document.getElementById('district-table');
            const districtsResponse = JSON.parse(this.response);
            let tableContent = `
                <tr>
                    <th><a href="./?sort=name">Nazwa</a></th>
                    <th><a href="./?sort=area">Powierzchnia</a></th>
                    <th><a href="./?sort=population">Populacja</a></th>
                    <th><a href="./?sort=city">Miasto</a></th>
                    <th>Usuń</th>
                </tr>`;
            for (const d of districtsResponse) {
                tableContent += `
                    <tr>
                        <td>${d.name}</td>
                        <td>${d.area}</td>
                        <td>${d.population}</td>
                        <td>${d.city}</td>
                        <td><a href="./delete?id=${d.id}">X</a></td>
                    </tr>`;
            }
            table.innerHTML = tableContent;
        }
    };
    xmlhttp.open('GET', './filter?json=' + districtsRequest, true);
    xmlhttp.send();
}