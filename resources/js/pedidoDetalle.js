function copiarCodigo(codigo) {
    navigator.clipboard.writeText(codigo)
        .then(() => {
            const container = document.getElementById('message');
            container.textContent = 'Código copiado: ' + codigo;
            container.classList.remove('d-none');
            setTimeout(() => container.classList.add('show'), 10);
            setTimeout(() => {
                container.classList.remove('show');
                setTimeout(() => container.classList.add('d-none'), 300);
            }, 2500);
        })
        .catch(err => {
            console.error('Error al copiar:', err);
        });
}

window.copiarCodigo = copiarCodigo;
