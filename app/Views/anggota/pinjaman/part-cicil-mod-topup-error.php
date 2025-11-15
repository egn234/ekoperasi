            <div class="modal-header">
                <h5 class="modal-title" id="addPengajuanLabel">Top Up Tidak Tersedia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <div class="mb-4">
                        <?php if($error_type == 'completed'): ?>
                            <div class="bg-success bg-opacity-10 text-success rounded-circle d-inline-flex align-items-center justify-content-center" 
                                 style="width: 80px; height: 80px; font-size: 32px;">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        <?php elseif($error_type == 'too_many_remaining'): ?>
                            <div class="bg-warning bg-opacity-10 text-warning rounded-circle d-inline-flex align-items-center justify-content-center" 
                                 style="width: 80px; height: 80px; font-size: 32px;">
                                <i class="fas fa-clock"></i>
                            </div>
                        <?php else: ?>
                            <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-inline-flex align-items-center justify-content-center" 
                                 style="width: 80px; height: 80px; font-size: 32px;">
                                <i class="fas fa-times-circle"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <h5 class="mb-3">
                        <?php if($error_type == 'completed'): ?>
                            Pinjaman Sudah Lunas
                        <?php elseif($error_type == 'too_many_remaining'): ?>
                            Sisa Cicilan Terlalu Banyak
                        <?php else: ?>
                            Pinjaman Tidak Aktif
                        <?php endif; ?>
                    </h5>
                    
                    <p class="text-muted mb-4"><?= $error_message ?></p>
                    
                    <?php if($error_type == 'too_many_remaining'): ?>
                        <div class="alert alert-warning border-0 mb-4" style="background-color: #fff3cd;">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-info-circle me-2 text-warning"></i>
                                <div class="text-start">
                                    <strong>Ketentuan Top Up:</strong><br>
                                    <small>Top Up hanya dapat dilakukan ketika sisa cicilan kurang dari atau sama dengan 2 bulan</small>
                                </div>
                            </div>
                        </div>
                        
                        <?php if(isset($remaining_installments)): ?>
                            <div class="card border-0 bg-light mb-3">
                                <div class="card-body text-center py-3">
                                    <div class="row">
                                        <div class="col-12">
                                            <span class="text-muted">Sisa Cicilan Saat Ini</span>
                                            <h4 class="mb-0 <?= $remaining_installments > 2 ? 'text-warning' : 'text-success' ?>">
                                                <?= $remaining_installments ?> Bulan
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Tutup
                </button>
            </div>