function actualizarFechaHora() {
    const ahora = new Date();
    
    const diasSemana = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
    const meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
    
    const diaSemana = diasSemana[ahora.getDay()];
    const dia = ahora.getDate();
    const mes = meses[ahora.getMonth()];
    const año = ahora.getFullYear();
    
    let horas = ahora.getHours();
    const minutos = ahora.getMinutes().toString().padStart(2, '0');
    
    const ampm = horas >= 12 ? 'PM' : 'AM';
    horas = horas % 12;
    horas = horas ? horas : 12;
    
    const fechaFormateada = `${diaSemana} ${dia} de ${mes} de ${año} - ${horas}:${minutos} ${ampm}`;
    
    document.getElementById('fechaHora').textContent = `${fechaFormateada}`;
}

actualizarFechaHora();
setInterval(actualizarFechaHora, 10000);