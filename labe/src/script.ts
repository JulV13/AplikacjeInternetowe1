const styles: Record<string, string> = {
    'Styl 1': 'style-1.css',
    'Styl 2': 'style-2.css',
    'Styl 3': 'style-3.css'
};

let aktualnyStyl: string = ''; 

function zmienStyl(nazwaStylu: string): void {

    if (aktualnyStyl === nazwaStylu) return;

    const nowyPlikStylu = styles[nazwaStylu];
    if (!nowyPlikStylu) {
        console.error(`Styl o nazwie "${nazwaStylu}" nie istnieje.`);
        return;
    }

    let linkStyl = document.getElementById('wygladApki') as HTMLLinkElement;
    
    if (linkStyl) {
        linkStyl.href = nowyPlikStylu;
    } else {
        linkStyl = document.createElement('link');
        linkStyl.rel = 'stylesheet';
        linkStyl.href = nowyPlikStylu;
        linkStyl.id = 'wygladApki';
        document.head.appendChild(linkStyl);
    }

    aktualnyStyl = nazwaStylu;
    console.log(`Zmieniono styl na: ${aktualnyStyl} (${nowyPlikStylu})`);
    
    aktualizujLinki();
}

function aktualizujLinki(): void {
    const zmieniacz = document.getElementById('zmieniaczStylu');
    if (!zmieniacz) return;

    zmieniacz.innerHTML = '';

    const label = document.createElement('span');
    label.textContent = 'Wybierz styl: ';
    zmieniacz.appendChild(label);

    Object.keys(styles).forEach(name => {
        const link = document.createElement('a');
        link.href = `#style-${encodeURIComponent(name)}`;
        link.textContent = name;
        
        if (name === aktualnyStyl) {
            link.classList.add('aktywnyStyl');
        }

        link.addEventListener('click', (event) => {
            event.preventDefault();
            zmienStyl(name);
        });

        zmieniacz.appendChild(link);
        zmieniacz.appendChild(document.createTextNode(' | '));
    });

    if (zmieniacz.lastChild?.textContent === ' | ') {
        zmieniacz.removeChild(zmieniacz.lastChild);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    zmienStyl('Styl 1'); 
    aktualizujLinki();
});