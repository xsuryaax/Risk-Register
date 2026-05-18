<div class="table-responsive p-0" id="tableWrapper" data-total="{{ $data->total() }}" data-count="{{ $data->count() }}">
    <table class="table align-items-center mb-0 table-bordered-light" id="mainTable">
        <thead class="bg-light">
            <tr>
                @if($isLibraryView)
                <th class="text-center px-2" style="width: 40px;">
                    <div class="form-check d-flex justify-content-center p-0">
                        <input class="form-check-input" type="checkbox" id="checkAll">
                    </div>
                </th>
                @endif
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1" style="width: 20px;">No</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1" style="width: 70px;">Kode</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1" style="width: 15%;">Kegiatan</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1">Kategori</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1" style="width: 20%;">Pernyataan</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1" style="width: 15%;">Sebab</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1">UC/C</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1" style="width: 15%;">Dampak</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $item)
            @php
                $isPulled = in_array($item->kegiatan, $pulledActivities);
            @endphp
            <tr class="{{ $isPulled ? 'bg-light-soft' : '' }}">
                @if($isLibraryView)
                <td class="align-middle text-center px-2">
                    <div class="form-check d-flex justify-content-center p-0">
                        <input class="form-check-input risk-checkbox" type="checkbox" value="{{ $item->id }}" 
                            {{ $isPulled ? 'disabled' : '' }}>
                    </div>
                </td>
                @endif
                <td class="align-middle text-center px-1">
                    <span class="text-dark text-xs font-weight-bold">{{ $loop->iteration + ($data->currentPage() - 1) * $data->perPage() }}</span>
                </td>

                <td class="align-middle text-center px-1">
                    <span class="text-xs font-weight-bold text-primary">{{ $item->kode_risiko }}</span>
                </td>
                <td class="px-1">
                    <p class="text-xs font-weight-bold mb-0 text-wrap text-dark" style="max-width: 200px;">{{ $item->kegiatan }}</p>
                </td>
                <td class="align-middle text-center px-1">
                    <p class="text-xs mb-0 text-wrap text-dark">{{ $item->kategori->nama_kategori ?? '-' }}</p>
                </td>
                <td class="px-1">
                    <p class="text-xs mb-0 text-wrap text-dark" style="max-width: 250px;">{{ $item->pernyataan_risiko }}</p>
                </td>
                <td class="px-1">
                    <p class="text-xs mb-0 text-wrap text-dark" style="max-width: 180px;">{{ $item->sebab }}</p>
                </td>
                <td class="align-middle text-center px-1">
                    <span class="text-xs font-weight-bold text-dark">{{ $item->jenis_risiko }}</span>
                </td>
                <td class="px-1">
                    <p class="text-xs mb-0 text-wrap text-dark" style="max-width: 180px;">{{ $item->dampak }}</p>
                </td>
                <td class="align-middle text-center px-1">
                    <div class="d-flex justify-content-center align-items-center">
                        @if($isLibraryView)
                            @if($isPulled)
                                <span class="badge badge-sm bg-success border-radius-sm" style="font-size: 0.6rem;">Sudah Ada</span>
                            @else
                                <button type="button" class="btn btn-xs text-white mb-0 btn-copy-risk border-radius-sm px-2 py-1" 
                                    style="background-color: #007774 !important;" data-id="{{ $item->id }}" title="Tarik ke Periode Aktif">
                                    <i class="fa fa-download me-1"></i> Tarik
                                </button>
                            @endif
                        @else
                            <a href="{{ route('pdf.profile', $item->id) }}?type=identifikasi" class="btn-action bg-info border-0 me-1" title="Cetak Profile PDF" target="_blank" style="background-color: #17a2b8 !important;">
                                <i class="fa fa-file-pdf text-white"></i>
                            </a>
                            <a href="{{ route('identifikasi-risiko.edit', $item->id) }}" class="btn-action btn-edit me-1" title="Edit Data">
                                <i class="fa fa-edit text-white"></i>
                            </a>
                            <form action="{{ route('identifikasi-risiko.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus identifikasi risiko ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action btn-delete" title="Hapus Data">
                                    <i class="fa fa-trash text-white"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="text-center py-6">
                    <div class="empty-state py-4">
                        <h6 class="text-secondary font-weight-bold">Data Kosong</h6>
                        <p class="text-xs text-muted">Belum ada identifikasi risiko untuk periode ini.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@if($data->hasPages())
<div class="card-footer py-3 px-0 border-0 bg-transparent">
    <div class="d-flex justify-content-center">
        {{ $data->links() }}
    </div>
</div>
@endif
