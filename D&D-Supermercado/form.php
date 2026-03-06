<?php
?>

<div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
    <div class="modal-body">

        <form id="registerForm" action="envio.php" method="POST">

            <div class="mb-3">
                <label for="registerName" class="form-label">Nome Completo</label>
                <input type="text" class="form-control" id="registerName" name="nome" required>
            </div>

            <div class="mb-3">
                <label for="registerBirthDate" class="form-label">Data de Nascimento</label>
                <input type="date" class="form-control" id="registerBirthDate" name="data_nasc" required>
            </div>

            <div class="mb-3">
                <label for="registerPhone" class="form-label">Telefone / Celular</label>
                <input type="tel" class="form-control" id="registerPhone" name="telefone" required>
            </div>

            <div class="mb-3">
                <label for="registerGender" class="form-label">Sexo</label>
                <select class="form-select" id="registerGender" name="sexo" required>
                    <option value="" selected disabled>Selecione...</option>
                    <option value="M">Masculino</option>
                    <option value="F">Feminino</option>
                    <option value="O">Outro</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="registerEmail" class="form-label">E-mail</label>
                <input type="email" class="form-control" id="registerEmail" name="email" required>
            </div>

            <div class="mb-3">
                <label for="registerPassword" class="form-label">Senha</label>
                <input type="password" class="form-control" id="registerPassword" name="senha" minlength="6" required>
            </div>

            <div class="mb-3">
                <label for="registerConfirmPassword" class="form-label">Confirmar Senha</label>
                <input type="password" class="form-control" id="registerConfirmPassword" name="confirma_senha"
                    minlength="6" required>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="termsCheck" required>
                <label class="form-check-label" for="termsCheck">
                    Eu concordo com os <a href="#">Termos de Serviço</a>.
                </label>
            </div>

            <button type="submit" class="btn btn-primary w-100 mt-3"
                style="background-color: var(--primaria, #be5c00); border-color: var(--primaria, #be5c00);">Cadastrar</button>
        </form>
    </div>
</div>

<div class="modal-footer justify-content-center">
    <p class="mb-0">
        Já tem uma conta?
        <a href="#" data-bs-toggle="modal" data-bs-target="#loginRegisterModal" data-bs-dismiss="modal"
            class="text-decoration-none">
            Faça Login aqui.
        </a>
    </p>
</div>

</div>
</div>
</div>