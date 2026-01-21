document.getElementById("przycisk").addEventListener("click", function(){

    var miasto = document.getElementById("miasto").value;
    var APIkey = "61d97553d9ff6559439d1e10cc413fd3";
    var apiCallCurrent = `https://api.openweathermap.org/data/2.5/weather?q=${miasto}&appid=${APIkey}&units=metric&lang=pl`;
    var apiCallForecast = `https://api.openweathermap.org/data/2.5/forecast?q=${miasto}&appid=${APIkey}&units=metric&lang=pl`;

    document.getElementById("panelAktualnejPogody").style.display = "block";
    document.getElementById("panelPogodyTerminowej").style.display = "block";


    // XMLHttpRequest

    const xml = new XMLHttpRequest();
    xml.open("GET", apiCallCurrent, true);

    xml.onreadystatechange = function() {

        if(xml.readyState === 4){
            console.log("Odpowiedz: ", xml.responseText);

            if (xml.status === 200) {
                var data = JSON.parse(xml.responseText);
                console.log("Dane: ", data);
                    
                if (data.cod === 200) {
    
                    let czas = new Date(data.dt * 1000);
                    let dzien = czas.toLocaleDateString("pl-PL", { weekday: "long" });
                    let temperatura = data.main.temp;
                    let temperaturaOdczuwalna = data.main.feels_like;
                    let opis = data.weather[0].description;
                    let miasto = data.name;
                    let cisnienie = data.main.pressure;
                    let wilgotnosc = data.main.humidity;
                    let predkoscWiatru = data.wind.speed;
                    
                    document.getElementById("panelAktualnejPogody").innerHTML = `
                        <h1 style="color: white">Aktualna pogoda</h1><hr>
                        <h4>${miasto} ${czas.toLocaleDateString("pl-PL")}</h4>
                        <h2 style="color: white">${dzien}, ${czas.toLocaleTimeString("pl-PL", {
                            hour: "2-digit",
                            minute: "2-digit"
                        })}</h2>
                        <img src="https://openweathermap.org/img/wn/${data.weather[0].icon}@2x.png">
                        <h2 style="color: white">${temperatura}°C</h2>
                        <h4>${opis}</h4><br>
                        <ul>
                        <li>Temperatura odczuwalna: <strong>${temperaturaOdczuwalna}°C</strong> </li><br>
                        <li>Cisnienie: <strong>${cisnienie} hPa</strong> </li><br>
                        <li>Wilgotnosc: <strong>${wilgotnosc}%</strong> </li><br>
                        <li>Predkosc wiatru: <strong>${predkoscWiatru} m/s</strong> </li><br>
                        </ul>
                    `;

                } else {
                    document.getElementById("panelAktualnejPogody").innerHTML = "Niepoprawna nazwa miasta.";
                }
            } else {
                document.getElementById("panelAktualnejPogody").innerHTML = "Blad podczas pobierania aktualnej pogody.";
            }
        }
    };

    xml.onerror = function() {
        document.getElementById("panelAktualnejPogody").innerHTML = "Blad podczas laczenia z API OpenWeatherMap.";
    };
    
    xml.send();


    // Fetch API

    fetch(apiCallForecast)
        .then(response => {
            if (!response.ok) {
                throw new Error("Blad serwera.");
            }
            console.log("Odpowiedz: ", response);
            return response.json();
        })
        .then(data => {
            if (data.cod !== "200") {
                document.getElementById("panelPogodyTerminowej").innerHTML = 
                    "Niepoprawna nazwa miasta.";
                return;
            }
            console.log("Dane: ", data);

            let html = `<h1 style="color: white">5-dniowa prognoza</h1><hr>`;

            data.list.forEach((entry, index) => {

                // co 12 godzin
                if (index % 4 !== 0) return;

                let czas = new Date(entry.dt * 1000);
                let dzien = czas.toLocaleDateString("pl-PL", { weekday: "long" });
                let temperatura = entry.main.temp;
                let temperaturaOdczuwalna = entry.main.feels_like;
                let opis = entry.weather[0].description;
                let ikona = entry.weather[0].icon;
                let miasto = data.city.name;
                let cisnienie = entry.main.pressure;
                let wilgotnosc = entry.main.humidity;
                let predkoscWiatru = entry.wind.speed;

                html += `
                    <div id="prognoza-element">
                        <h4>${miasto} ${czas.toLocaleDateString("pl-PL")}</h4>
                        <h2 style="color: white">${dzien}, ${czas.toLocaleTimeString("pl-PL", {
                            hour: "2-digit",
                            minute: "2-digit"
                        })}</h2>
                        <img src="https://openweathermap.org/img/wn/${ikona}@2x.png">
                        <h2 style="color: white">${temperatura}°C</h2>
                        <h4>${opis}</h4><br>
                        <ul>
                        <li><strong>Temperatura odczuwalna:</strong> ${temperaturaOdczuwalna}°C</li><br>
                        <li><strong>Cisnienie:</strong> ${cisnienie} hPa </li><br>
                        <li><strong>Wilgotnosc:</strong> ${wilgotnosc}%</li><br>
                        <li><strong>Predkosc wiatru:</strong> ${predkoscWiatru} m/s</li><br>
                        </ul>
                    </div>
                `;
            });

            document.getElementById("panelPogodyTerminowej").innerHTML = html;
        })
        .catch(error => {
            document.getElementById("panelPogodyTerminowej").innerHTML =
                "Blad podczas pobierania terminowej prognozy pogody.";
        });

});