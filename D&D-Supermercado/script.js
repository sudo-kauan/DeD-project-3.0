// CARRINHO
document.addEventListener('DOMContentLoaded', () => {
  // const cartButton = document.getElementById('cartButton'); // Não está sendo usado diretamente
  const cartCountSpan = document.getElementById('cartCount');
  const cartList = document.getElementById('cartList');
  const cartTotalEl = document.getElementById('cartTotal');

  //Toast
  const liveToast = document.getElementById('liveToast');
  const toastBootstrap = typeof bootstrap !== 'undefined' ? bootstrap.Toast.getOrCreateInstance(liveToast) : null;

  let cart = [];

  // Função para alterar a quantidade do item no carrinho
  const changeItemQty = (index, action) => {
    if (index === undefined || cart[index] === undefined) return;

    if (action === 'plus') {
      cart[index].qty += 1;
    } else if (action === 'minus') {
      cart[index].qty -= 1;
      // Remove o item se a quantidade chegar a zero
      if (cart[index].qty <= 0) {
        cart.splice(index, 1);
      }
    }
    updateCartUI();
  };

  const updateCartUI = () => {
    // Recalcula o índice total do cart
    const totalQty = cart.reduce((sum, it) => sum + it.qty, 0);

    // Atualiza o Badge no cabeçalho
    cartCountSpan.textContent = totalQty;

    if (cart.length === 0) {
      cartList.innerHTML = '<p class="cart-empty">Seu carrinho está vazio</p>';
      cartTotalEl.textContent = '0,00';
      return;
    }

    cartList.innerHTML = '';
    cart.forEach((item, i) => {
      // Calcula o total do item
      const itemTotalPrice = item.price * item.qty;

      const li = document.createElement('div');
      li.className = 'cart-item d-flex align-items-center mb-3 pb-2 border-bottom';

      // Estrutura de imagem, info e botões +/-
      li.innerHTML = `
                <img src="${item.img || 'imgs/placeholder.png'}" alt="${item.name}" class="me-2" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                <div class="cart-item-details flex-grow-1">
                    <h5 class="mb-0 fs-6">${item.name}</h5>
                    <p class="text-muted small mb-1">R$ ${item.price.toFixed(2).replace('.', ',')} / un.</p>

                    <div class="input-group input-group-sm mb-1" style="width: 100px;">
                        <button data-index="${i}" data-action="minus" class="btn btn-outline-secondary btn-qty">-</button>
                        <input type="text" class="form-control text-center" value="${item.qty}" readonly style="pointer-events: none;">
                        <button data-index="${i}" data-action="plus" class="btn btn-outline-secondary btn-qty">+</button>
                    </div>
                </div>

                <div class="ms-3 text-end d-flex flex-column justify-content-center align-items-end" style="width: 80px;">
                    <p class="fw-bold mb-1">R$ ${itemTotalPrice.toFixed(2).replace('.', ',')}</p>
                    <button data-index="${i}" class="remove-item btn btn-sm btn-outline-danger mt-1">Remover</button>
                </div>
            `;
      cartList.appendChild(li);
    });

    const total = cart.reduce((s, it) => s + (it.price * it.qty), 0);
    cartTotalEl.textContent = total.toFixed(2).replace('.', ',');

    // Manipulador de eventos para os botões +/- e Remover
    cartList.querySelectorAll('.btn-qty, .remove-item').forEach(btn => {
      btn.addEventListener('click', (e) => {
        const target = e.target.closest('button');
        if (!target) return;

        const idx = Number(target.dataset.index);
        const action = target.dataset.action;

        if (target.classList.contains('btn-qty')) {
          // Lógica para botões + e -
          changeItemQty(idx, action);
        } else if (target.classList.contains('remove-item')) {
          // Lógica para o botão Remover
          if (!Number.isNaN(idx)) {
            cart.splice(idx, 1);
            updateCartUI();
          }
        }
      });
    });
  };

  window.addItemToCart = (item) => {
    if (!item || !item.id) return;
    const existing = cart.find(i => i.id === item.id);

    if (existing) {
      existing.qty += item.qty || 1;
    } else {
      cart.push({
        id: item.id,
        name: item.name || 'Produto',
        price: item.price || 0,
        qty: item.qty || 1,
        img: item.img || ''
      });
    }

    updateCartUI();

    // Exibe o Toast de confirmação, se disponível
    if (toastBootstrap) {
      toastBootstrap.show();
    }
  };

  document.querySelectorAll('.btn-comprar').forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();

      // Seletor .card
      const productCard = btn.closest('.card');

      if (!productCard) {
        console.error("Não foi possível encontrar o elemento pai '.card'. Verifique a estrutura HTML.");
        return;
      }

      // Captura de dados (ID, Nome e Imagem)
      const id = productCard.dataset.id || Date.now();
      const name = productCard.querySelector('.card-title')?.textContent?.trim() || 'Produto';

      let price = parseFloat(productCard.dataset.price) || 0;
      if (price === 0) {
        const priceText = productCard.querySelector('.card-text')?.textContent || 'R$ 0,00';
        const match = priceText.match(/R\$ ([\d,.]+)/);
        if (match) {
          price = parseFloat(match[1].replace(/\./g, '').replace(',', '.')) || 0;
        }
      }

      const img = productCard.querySelector('img')?.src || '';

      window.addItemToCart({ id, name, price, qty: 1, img });
    });
  });

  updateCartUI();
});

//CEP
document.addEventListener('DOMContentLoaded', function () {
  const cepInput = document.getElementById('cepInput');

  if (cepInput) {

    cepInput.addEventListener('input', function () {
      let valor = this.value.replace(/\D/g, '');

      if (valor.length > 5) {
        let parte1 = valor.substring(0, 5);
        let parte2 = valor.substring(5, 8);

        valor = parte1 + '-' + parte2;

      } else if (valor.length > 8) {
        valor = valor.substring(0, 8);
      }

      this.value = valor;
    });
  }
});

// Telefone
document.addEventListener('DOMContentLoaded', function () {
  const phoneInput = document.getElementById('registerPhone');

  if (phoneInput) {
    phoneInput.addEventListener('input', function (e) {
      let x = e.target.value.replace(/\D/g, '').substring(0, 11);

      let formatted = '';

      if (x.length > 0) {
        formatted = '(' + x.substring(0, 2);
      }

      if (x.length > 2) {
        formatted += ') ' + x.substring(2, 7);
      }

      if (x.length > 7) {
        formatted += '-' + x.substring(7, 11);
      }

      e.target.value = formatted;
    });
  }
});