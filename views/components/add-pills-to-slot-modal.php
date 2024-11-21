<div class="modal fade" id="modalAddPillsToSlot"
    data-bs-backdrop="static"
    data-bs-keyboard="false"
    tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= fullPath('controllers/medicines.php', true) ?>" method="POST">
                <input
                    type="hidden" id=""
                    name="medicines_action" value="remove_pills_from_stock">
                <input
                    type="hidden" id="hiddenAddPillsPersonInCareId"
                    name="person_in_care_id" value="<?= $_GET['id'] ?>">
                <input
                    type="hidden" id="hiddenAddPillsMedicineId"
                    name="medicine_id" value="">
                <input
                    type="hidden" id="hiddenAddPillSlotName"
                    name="slot_name" value="">
                <input
                    type="hidden" id=""
                    name="redirect_url" value="<?= fullPath('views/pages/person-in-care-profile.php', true) ?>">

                <div class="modal-header">
                    <h1 class="modal-title fs-5">
                        Adicionar comprimidos ao compartimento "<span id="spanAddPillsSlotName"></span>"
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="txtAddPillsPersonName" class="form-label">Pessoa sob Cuidado</label>
                        <input
                            type="text" id="txtAddPillsPersonName"
                            name="" class="form-control"
                            required disabled>
                    </div>
                    <div class="mb-3">
                        <label for="txtAddPillsMedicineName" class="form-label">Medicamento</label>
                        <input
                            type="text" id="txtAddPillsMedicineName"
                            name="" class="form-control"
                            required disabled>
                    </div>
                    <div class="mb-3">
                        <label for="numPillsInStock" class="form-label">Comprimidos em Estoque</label>
                        <input
                            type="number" id="numPillsInStock"
                            name="pills_per_dose" class="form-control"
                            required disabled>
                    </div>
                    <div class="mb-3">
                        <label for="numPillsInSlot" class="form-label">Comprimidos no Compartimento</label>
                        <input
                            type="number" id="numPillsInSlot"
                            name="" class="form-control"
                            required disabled>
                    </div>
                    <div class="mb-3">
                        <label for="numPillsAdded" class="form-label">Comprimidos a Serem Adicionados</label>
                        <input
                            type="number" id="numPillsAdded"
                            name="pills_added_to_slot" class="form-control"
                            required value="1" min="1" max="">
                    </div>
                    <div class="d-flex justify-content-center py-2">
                        <div class="w-75">
                            <p class="fs-5 fw-bold text-danger text-center">Atenção</p>
                            <p class="text-center">Apenas finalize o processo após adicionar os comprimidos no compartimento da caixa!</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Adicionar Comprimidos</button>
                </div>
            </form>
        </div>
    </div>
</div>