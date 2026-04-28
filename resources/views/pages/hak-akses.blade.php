@extends('layouts.app')

@section('title', 'Hak Akses - Risk Register')
@section('breadcrumb', 'Master Data')
@section('page_title', 'Hak Akses')
@section('page_description', 'Kelola permissions dan hak akses menu dengan ukuran yang proporsional.')

@section('content')
<div class="row">
    <div class="col-12">
        {{-- WRAPPER CARD UTAMA --}}
        <div class="card shadow-sm border-0 border-radius-lg bg-white p-3">
            <div class="card-body p-1">
                
                {{-- CARD PILIH ROLE --}}
                <div class="card mb-4 border-radius-lg shadow-none border bg-gray-100">
                    <div class="card-body p-3">
                        <form method="GET" action="{{ route('hak-akses.index') }}">
                            <div class="row align-items-center">
                                <div class="col-md-5">
                                    <label class="text-xs font-weight-bold text-muted mb-1">Pilih Role Pengguna</label>
                                    <select name="role_id" class="form-select select-search border-radius-md" onchange="this.form.submit()" data-placeholder="Pilih Role...">
                                        <option value=""></option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}" {{ $selectedRole == $role->id ? 'selected' : '' }}>
                                                {{ $role->nama_role }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- FORM HAK AKSES --}}
                <form method="POST" action="{{ route('hak-akses.update', $selectedRole) }}">
                    @csrf
                    @foreach($menuStructure as $groupKey => $group)
                    <div class="card mb-4 border-radius-lg shadow-none border">
                        <div class="card-body p-4">
                            <h6 class="fw-bold mb-3 d-flex align-items-center text-dark">
                                <span class="me-2 text-primary" style="font-size: 1.1rem;">📁</span> {{ $group['title'] }}
                            </h6>
                            
                            <div class="row g-3">
                                @foreach($group['menus'] as $menu)
                                <div class="col-md-6">
                                    <div class="border rounded-3 p-3 d-flex align-items-center menu-card-simmutu {{ in_array($menu['key'], $currentAkses) ? 'is-active' : '' }}" 
                                         style="cursor: pointer; transition: all 0.2s;"
                                         onclick="toggleCheck('{{ $menu['key'] }}')">
                                        <div class="form-check m-0 d-flex align-items-center">
                                            <input class="form-check-input custom-check-green" type="checkbox" name="menu_key[]" 
                                                   id="check-{{ $menu['key'] }}" 
                                                   value="{{ $menu['key'] }}"
                                                   {{ in_array($menu['key'], $currentAkses) ? 'checked' : '' }}
                                                   style="width: 1.2rem; height: 1.2rem;">
                                            <label class="form-check-label fw-bold text-dark ms-2 mb-0" for="check-{{ $menu['key'] }}" style="cursor: pointer; font-size: 0.85rem;">
                                                {{ $menu['label'] }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endforeach

                    <div class="text-end mt-4">
                        <button type="submit" class="btn bg-primary text-white px-5 shadow-sm mb-0">
                            <i class="fa fa-save me-2"></i> Simpan Hak Akses
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

@push('css')
<style>
    .menu-card-simmutu {
        border-color: #dee2e6 !important;
        background-color: #fff;
    }
    .menu-card-simmutu.is-active {
        background-color: rgba(0, 119, 116, 0.05) !important;
        border-color: #007774 !important;
        border-width: 1.5px !important;
    }
    .custom-check-green:checked {
        background-color: #007774 !important;
        border-color: #007774 !important;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='%23fff' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='M6 10l3 3l6-6'/%3e%3c/svg%3e") !important;
    }
    .menu-card-simmutu:hover {
        background-color: #f8f9fa;
        transform: translateY(-1px);
    }
</style>
@endpush

@push('js')
<script>
    function toggleCheck(key) {
        const check = document.getElementById('check-' + key);
        const card = check.closest('.menu-card-simmutu');
        
        check.checked = !check.checked;
        
        if (check.checked) {
            card.classList.add('is-active');
        } else {
            card.classList.remove('is-active');
        }
    }
    
    document.querySelectorAll('.form-check-input, .form-check-label').forEach(el => {
        el.addEventListener('click', (e) => e.stopPropagation());
    });
</script>
@endpush
@endsection
