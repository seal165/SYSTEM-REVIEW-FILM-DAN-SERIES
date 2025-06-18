

<?php $__env->startSection('title', 'Movie Details'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4">
    <h1 class="mt-4">Movie Details</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?php echo e(route('movies.index')); ?>">Movies</a></li>
        <li class="breadcrumb-item active"><?php echo e($movie->title); ?></li>
    </ol>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-film me-1"></i>
                        Movie Information
                    </div>
                    <div>
                        <span class="badge bg-<?php echo e($movie->status == 'now_showing' ? 'success' : ($movie->status == 'coming_soon' ? 'warning' : 'secondary')); ?>">
                            <?php echo e(ucfirst(str_replace('_', ' ', $movie->status))); ?>

                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-3">
                            <strong>Title:</strong>
                        </div>
                        <div class="col-sm-9">
                            <h4 class="mb-0"><?php echo e($movie->title); ?></h4>
                        </div>
                    </div>
                    
                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-3">
                            <strong>Genre:</strong>
                        </div>
                        <div class="col-sm-9">
                            <span class="badge bg-primary me-1"><?php echo e($movie->genre); ?></span>
                        </div>
                    </div>
                    
                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-3">
                            <strong>Duration:</strong>
                        </div>
                        <div class="col-sm-9">
                            <i class="fas fa-clock me-1"></i><?php echo e($movie->duration); ?> minutes
                        </div>
                    </div>
                    
                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-3">
                            <strong>Release Date:</strong>
                        </div>
                        <div class="col-sm-9">
                            <i class="fas fa-calendar me-1"></i><?php echo e(\Carbon\Carbon::parse($movie->release_date)->format('F d, Y')); ?>

                        </div>
                    </div>
                    
                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-3">
                            <strong>Rating:</strong>
                        </div>
                        <div class="col-sm-9">
                            <span class="badge bg-info"><?php echo e($movie->rating); ?></span>
                            <?php switch($movie->rating):
                                case ('G'): ?>
                                    - General Audiences
                                    <?php break; ?>
                                <?php case ('PG'): ?>
                                    - Parental Guidance
                                    <?php break; ?>
                                <?php case ('PG-13'): ?>
                                    - Parents Strongly Cautioned
                                    <?php break; ?>
                                <?php case ('R'): ?>
                                    - Restricted
                                    <?php break; ?>
                                <?php case ('NC-17'): ?>
                                    - Adults Only
                                    <?php break; ?>
                            <?php endswitch; ?>
                        </div>
                    </div>
                    
                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-3">
                            <strong>Ticket Price:</strong>
                        </div>
                        <div class="col-sm-9">
                            <span class="h5 text-success">
                                <i class="fas fa-dollar-sign"></i><?php echo e(number_format($movie->price, 2)); ?>

                            </span>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong>Description:</strong>
                        </div>
                        <div class="col-sm-9">
                            <p class="mb-0"><?php echo e($movie->description); ?></p>
                        </div>
                    </div>
                    
                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-3">
                            <strong>Created:</strong>
                        </div>
                        <div class="col-sm-9">
                            <small class="text-muted">
                                <?php echo e($movie->created_at->format('F d, Y \a\t g:i A')); ?>

                            </small>
                        </div>
                    </div>
                    
                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-3">
                            <strong>Last Updated:</strong>
                        </div>
                        <div class="col-sm-9">
                            <small class="text-muted">
                                <?php echo e($movie->updated_at->format('F d, Y \a\t g:i A')); ?>

                            </small>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-cogs me-1"></i>
                    Actions
                </div>
                <div class="card-body">
                    <a href="<?php echo e(route('movies.edit', $movie->id)); ?>" class="btn btn-warning me-2">
                        <i class="fas fa-edit"></i> Edit Movie
                    </a>
                    <a href="<?php echo e(route('movies.index')); ?>" class="btn btn-secondary me-2">
                        <i class="fas fa-arrow-left"></i> Back to Movies
                    </a>
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <i class="fas fa-trash"></i> Delete Movie
                    </button>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-image me-1"></i>
                    Movie Poster
                </div>
                <div class="card-body text-center">
                    <?php if($movie->poster_url): ?>
                        <img src="<?php echo e($movie->poster_url); ?>" class="img-fluid rounded shadow" 
                             style="max-height: 400px;" alt="<?php echo e($movie->title); ?> Poster">
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-film fa-5x text-muted mb-3"></i>
                            <p class="text-muted">No poster available</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Quick Stats -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-chart-bar me-1"></i>
                    Quick Stats
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <div class="h4 mb-0"><?php echo e($movie->duration); ?></div>
                                <small class="text-muted">Minutes</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="h4 mb-0">$<?php echo e(number_format($movie->price, 2)); ?></div>
                            <small class="text-muted">Price</small>
                        </div>
                    </div>
                    <hr>
                    <div class="text-center">
                        <div class="h5 mb-0"><?php echo e($movie->rating); ?></div>
                        <small class="text-muted">Rating</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the movie <strong>"<?php echo e($movie->title); ?>"</strong>?</p>
                <p class="text-danger"><small><i class="fas fa-exclamation-triangle"></i> This action cannot be undone.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="<?php echo e(route('movies.destroy', $movie->id)); ?>" method="POST" class="d-inline">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Delete Movie
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\si_bioskop\resources\views/admin/movies/show.blade.php ENDPATH**/ ?>