GetBoletas();
async function AbrirModalBoleta(idBoleta){
    const boleta = await GetBoletaById(idBoleta);
    const [detalleCompras, cliente, horarioCaja] = await Promise.all([
        GetDetalleComprasByIdBoleta(boleta.id),
        GetUsuarioById(boleta.cliente),
        GetHorarioCajaById(boleta.horarioCaja),
    ]);
    let total = 0;
    let productos = [];
    let cantidades = [];
    let precios = [];
    let totales = [];
    let trProductos = [];
    const cajeroVirtual = await GetUsuarioById(horarioCaja.encargado);
    const caja = await GetCajaById(horarioCaja.caja);
    for (const item of detalleCompras) {
        let producto = await GetProductoById(item.producto);
        let precio = await GetUltimoPrecioHistoricoByIdProductoByFecha(item.producto, boleta.fecha);
        productos.push(producto.nombre);
        cantidades.push(item.cantidad);
        precios.push(precio.precio);
        totales.push(precio.precio * item.cantidad);
        total += precio.precio * item.cantidad;
    }
    let tipo = "";
    if(caja.tipo == "V"){
        tipo = "Virtual";
    }
    else{
        tipo = "Presencial";
    }
    let fecha = new Date(boleta.fecha).toISOString().split('T')[0];
    let hora = new Date(boleta.fecha).toISOString().split('T')[1].split('.')[0];
    document.getElementById('IdBoleta').textContent = `ID: ${boleta.id}`;
    document.getElementById('FechaBoleta').textContent = `Fecha: ${fecha}`;
    document.getElementById('HoraBoleta').textContent = `Hora: ${hora}`;
    document.getElementById('Cliente').textContent = `Cliente: ${cliente.nombreUsuario}`;
    document.getElementById('Cajero').textContent = `Cajero: ${cajeroVirtual.nombreUsuario}`;
    document.getElementById('Caja').textContent = `Tipo de caja: ${tipo}`;
    for (let i = 0; i < productos.length; i++) {
        trProductos.push(`
            <tr class="table table-striped table-bordered w-100">
                <td style="width: 50%;">${productos[i]}</td>
                <td style="width: 10%;"> $${precios[i]}</td>
                <td style="width: 20%;"> x ${cantidades[i]} uds.</td>
                <td style="width: 20%;"> = $${totales[i]}</td>
            </tr>
            <br>
        `);
    }
    document.getElementById('tblProductos').innerHTML = trProductos.join('');
    document.getElementById('Total').textContent = `Total: $${total.toFixed(2)}`;
    var DetalleModal = new bootstrap.Modal(document.getElementById('DetalleModal'));
    DetalleModal.show();

}
document.getElementById('cmbFecha').addEventListener('change', async function() {
    let mes = document.getElementById('cmbFecha').value;
    if (mes == "DefaultFecha") {
        await GetBoletas();
    } else if (mes) {
        await GetBoletasByMes(mes);
    }
});

document.getElementById('FechaBusqueda').addEventListener('change', async function() {
    let fecha = document.getElementById('FechaBusqueda').value;
    if (fecha) {
        boleta = await GetBoletasByFecha(fecha);
    }
    else{
        await GetBoletas();
    }
});

async function BuscarBoletaPorId(){
    let id = document.getElementById('IdBusqueda').value;
    if (id) {
        AbrirModalBoleta(id);
    }
    else{
        await GetBoletas();
    }
}

async function AnularCompra(){
    idBoleta = document.getElementById('IdBoleta').textContent.split(' ')[1];
    detalleCompras = await GetDetalleComprasByIdBoleta(idBoleta);
    for (const item of detalleCompras) {
        await DelDetalleCompra(item.id);
    }
    await DelBoleta(idBoleta);
}