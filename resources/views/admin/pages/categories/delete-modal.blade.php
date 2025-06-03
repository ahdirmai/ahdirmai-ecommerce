 <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered">
         <div class="modal-content border-0 shadow">
             <div class="modal-header border-bottom-0 pb-0">
                 <h5 class="modal-title" id="deleteModalLabel">
                     <i class="bi bi-exclamation-triangle text-warning me-2"></i>
                     Confirm Deletion
                 </h5>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
             </div>
             <div class="modal-body pt-0">
                 <p class="mb-3">Are you sure you want to delete the category <strong
                         id="deleteCategoryName"></strong>?</p>
                 <div class="alert alert-warning border-warning bg-warning bg-opacity-10">
                     <i class="bi bi-info-circle me-2"></i>
                     <strong>Warning:</strong> This action cannot be undone. Products associated with this category
                     may be affected.
                 </div>
             </div>
             <div class="modal-footer border-top-0 pt-0">
                 <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                     <i class="bi bi-x-lg me-1"></i>Cancel
                 </button>
                 <form id="deleteForm" method="POST" style="display: inline;" action="">
                     @csrf
                     @method('DELETE')
                     <button type="submit" class="btn btn-danger">
                         <i class="bi bi-trash me-1"></i>Delete Category
                     </button>
                 </form>
             </div>
         </div>
     </div>
 </div>
