<div class="mb-3">
    <label class="form-label">{{ __('messages.gambar') }}</label>
    @if(!empty($ziPage->gambar))
        <div class="mb-2">
            @if(in_array(pathinfo($ziPage->gambar, PATHINFO_EXTENSION), ['mp4','mov','avi','mkv','webm']))
                <video src="{{ asset('storage/' . $ziPage->gambar) }}" controls style="max-height:200px;border-radius:6px;" class="d-block mb-1"></video>
            @else
                <img src="{{ asset('storage/' . $ziPage->gambar) }}" alt="" style="max-height:200px;border-radius:6px;" class="d-block mb-1">
            @endif
            <label class="form-check-label">
                <input type="checkbox" name="hapus_gambar" value="1" class="form-check-input"> Hapus file ini
            </label>
        </div>
    @endif
    <input type="file" name="gambar" class="form-control @error('gambar') is-invalid @enderror" accept="image/*,video/*">
    <small class="text-muted">Format: JPG, PNG, GIF, WEBP, MP4, MOV, AVI, MKV (Ukuran bebas)</small>
    @error('gambar') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
<div class="mb-3">
    <label class="form-label">{{ __('messages.judul') }}</label>
    <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror" value="{{ old('judul', $ziPage->judul ?? '') }}">
    @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
<div class="mb-3">
    <label class="form-label">{{ __('messages.admin_keterangan') }}</label>
    <textarea name="konten" class="form-control @error('konten') is-invalid @enderror" rows="6">{{ old('konten', $ziPage->konten ?? '') }}</textarea>
    @error('konten') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
<div class="mb-3">
    <label class="form-label">{{ __('messages.form_file_pdf') }}</label>
    @if(!empty($ziPage->pdf_path))
        <div class="mb-2">
            <a href="{{ asset('storage/' . $ziPage->pdf_path) }}" target="_blank" class="badge bg-danger"><i class="fas fa-file-pdf"></i> Lihat PDF</a>
            <label class="form-check-label ms-2">
                <input type="checkbox" name="hapus_pdf" value="1" class="form-check-input"> Hapus PDF ini
            </label>
        </div>
    @endif
    <input type="file" name="pdf" class="form-control @error('pdf') is-invalid @enderror" accept="application/pdf">
    <small class="text-muted">Format: PDF (Ukuran bebas)</small>
    @error('pdf') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
<div class="mb-3">
    <label class="form-label">Foto / Video Tambahan</label>
    @if(!empty($ziPage) && $ziPage->media->count())
        <div class="row g-2 mb-2">
            @foreach($ziPage->media as $media)
                <div class="col-auto">
                    <div class="position-relative" style="display:inline-block;">
                        @if($media->is_video)
                            <video src="{{ asset('storage/' . $media->file_path) }}" style="height:80px;border-radius:6px;" muted></video>
                            <span class="badge bg-dark position-absolute top-0 start-0 m-1" style="font-size:0.6rem;"><i class="fas fa-video"></i></span>
                        @else
                            <img src="{{ asset('storage/' . $media->file_path) }}" style="height:80px;border-radius:6px;">
                        @endif
                        <label class="position-absolute top-0 end-0 m-1" style="cursor:pointer;">
                            <input type="checkbox" name="hapus_media[]" value="{{ $media->id }}" class="form-check-input" style="width:16px;height:16px;">
                        </label>
                    </div>
                </div>
            @endforeach
        </div>
        <small class="text-muted d-block mb-1"><i class="fas fa-info-circle"></i> Centang untuk menghapus file.</small>
    @endif
    <input type="file" name="media_files[]" class="form-control @error('media_files.*') is-invalid @enderror" accept="image/*,video/*" multiple>
    <small class="text-muted">Bisa upload banyak foto & video sekaligus (Ukuran bebas)</small>
    @error('media_files.*') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
