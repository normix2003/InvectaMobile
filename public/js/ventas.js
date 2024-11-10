function toggleBoton() {
    const boton = document.getElementById("btn-fin");
    let productos = JSON.parse(localStorage.getItem("productos")) || [];

    if (productos.length === 0) {
        boton.style.display = "none"; // Ocultar si no hay productos
    } else {
        boton.style.display = "block"; // Mostrar si hay productos
    }
}
document.addEventListener("DOMContentLoaded", function () {
    toggleBoton();

    document.querySelectorAll(".btn-add").forEach((button) => {
        button.addEventListener("click", function () {
            const productId = this.getAttribute("data-id");
            const row = this.closest("tr");
            const nombreProducto =
                row.querySelector("td:nth-child(1)").textContent;
            const precioProducto =
                row.querySelector("td:nth-child(6)").textContent;

            let producto = {
                id: productId,
                nombre: nombreProducto,
                precio: precioProducto,
                cantidad: 1,
            };

            let productos = JSON.parse(localStorage.getItem("productos")) || [];

            // Verificar si el producto ya está en la lista
            const existe = productos.find((p) => p.id === producto.id);
            if (existe) {
                existe.cantidad++;
                localStorage.setItem("productos", JSON.stringify(productos));
                toggleBoton();
                location.reload();
                return;
            }
            productos.push(producto);
            localStorage.setItem("productos", JSON.stringify(productos));
            toggleBoton();
            location.reload();
        });
    });

    const lista = document.getElementById("productos-lista");
    let productos = JSON.parse(localStorage.getItem("productos")) || [];

    if (productos.length === 0) {
        lista.innerHTML = `
        <tr>
            <td colspan="4" id="mensaje-vacio" class="text-center">No hay productos en la lista</td>
        </tr>
        `;
    }
    productos.forEach((producto, index) => {
        const row = document.createElement("tr");
        row.innerHTML = ` 
        <tr class="table-rows">
        <td class="px-3 text-center">${producto.nombre}</td>
        <td class="px-3 text-center"><input type="number" name="Cantidad" id="cantidad" data-index=${index} class="cantidad-input" value="${producto.cantidad}" min="1"></td>
        <td class="px-3 text-center">${producto.precio}</td>
        <td class="px-3 text-center"><button type="button" class="btn-delete" data-index=${index}">x</button></td>
        </tr>
    `;
        lista.appendChild(row);
    });

    document.querySelectorAll(".btn-delete").forEach((button) => {
        button.addEventListener("click", function () {
            const index = this.getAttribute("data-index");
            let productos = JSON.parse(localStorage.getItem("productos")) || [];
            productos.splice(index, 1);
            localStorage.setItem("productos", JSON.stringify(productos));
            toggleBoton();
            location.reload();
        });
    });

    const form = document.getElementById("finalizar-form");
    const productosData = document.getElementById("productos-data");
    const cantidad = document.getElementById("cantidad");
    form.addEventListener("submit", function () {
        let productos = JSON.parse(localStorage.getItem("productos")) || [];
        productosData.value = JSON.stringify(productos);
        //localStorage.removeItem('productos');
    });

    document.querySelectorAll(".cantidad-input").forEach((cantidad) => {
        cantidad.addEventListener("change", function () {
            const index = this.getAttribute("data-index");

            // Obtener la lista de productos del localStorage
            let productos = JSON.parse(localStorage.getItem("productos")) || [];

            // Actualizar solo la cantidad del producto en el índice correcto
            productos[index].cantidad = this.value;

            // Guardar la lista actualizada en el localStorage
            localStorage.setItem("productos", JSON.stringify(productos));
        });
    });
});
