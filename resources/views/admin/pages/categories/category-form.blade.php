 <div class="col-xl-4 col-lg-5 mb-4">
     <div class="card border-0 shadow-sm">
         <div class="card-header bg-white border-bottom">
             <h5 class="card-title mb-0" id="formTitle">
                 <i class="bi bi-plus-circle me-2"></i>
                 Add New Category
             </h5>
         </div>
         <div class="card-body">
             <form method="POST" action="{{ route('admin.categories.store') }}" novalidate id="categoryForm">
                 @csrf
                 {{-- Category Icon Font Awsome Icon --}}
                 <div class="mb-3">
                     <label for="icon" class="form-label fw-medium">
                         Category Icon <span class="text-danger">*</span>
                     </label>
                     <input type="text" class="form-control @error('icon') is-invalid @enderror" id="icon"
                         name="icon" value="{{ old('icon', $category->icon ?? '') }}"
                         placeholder="Enter icon class (e.g., fa-solid fa-box)" required maxlength="100"
                         autocomplete="off">
                     @error('icon')
                         <div class="invalid-feedback">{{ $message }}</div>
                     @enderror
                     <div class="form-text">
                         <small>Use Font Awesome icon classes (e.g., fa-solid fa-box)</small>
                     </div>
                 </div>

                 <!-- Category Name -->
                 <div class="mb-3">
                     <label for="name" class="form-label fw-medium">
                         Category Name <span class="text-danger">*</span>
                     </label>
                     <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                         name="name" value="{{ old('name', $category->name ?? '') }}"
                         placeholder="Enter category name" required maxlength="100" autocomplete="off">
                     @error('name')
                         <div class="invalid-feedback">{{ $message }}</div>
                     @enderror
                     <div class="form-text">
                         <small>Enter a unique name (2-100 characters)</small>
                     </div>
                 </div>

                 {{-- category Description --}}
                 <div class="mb-3">
                     <label for="description" class="form-label fw-medium">
                         Category Description
                     </label>
                     <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                         rows="3" placeholder="Enter category description (optional)" maxlength="500">{{ old('description', $category->description ?? '') }}</textarea>
                     @error('description')
                         <div class="invalid-feedback">{{ $message }}</div>
                     @enderror
                     <div class="form-text">
                         <small>Optional description (max 500 characters)</small>
                     </div>
                 </div>

                 <!-- Category Type -->
                 <div class="mb-4">
                     <label for="type" class="form-label fw-medium">
                         Category Type <span class="text-danger">*</span>
                     </label>
                     <select class="form-select @error('type') is-invalid @enderror" id="type" name="type"
                         required>
                         <option value="">-- Select Type --</option>
                         <option value="digital"
                             {{ old('type', $category->type ?? '') == 'digital' ? 'selected' : '' }}>Digital
                             Products</option>
                         <option value="physical"
                             {{ old('type', $category->type ?? '') == 'physical' ? 'selected' : '' }}>
                             Physical Products</option>
                     </select>
                     @error('type')
                         <div class="invalid-feedback">{{ $message }}</div>
                     @enderror
                     <div class="form-text">
                         <small>Choose product type for this category</small>
                     </div>
                 </div>

                 <!-- Form Actions -->

             </form>
             <div class="d-grid gap-2" id="formActions">
                 <button type="submit" class="btn btn-primary" id="submitBtn" form="categoryForm">
                     <i class="bi bi-plus-lg me-1"></i>
                     Create Category
                 </button>
             </div>
         </div>
     </div>
 </div>
