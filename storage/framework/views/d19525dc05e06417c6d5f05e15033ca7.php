

<?php $__env->startSection('title', 'Movies Management'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="mt-4">Movies Management</h1>
        <a href="<?php echo e(route('movies.create')); ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Movie
        </a>
    </div>
    
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
        <li class="breadcrumb-item active">Movies</li>
    </ol>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <?php $__empty_1 = true; $__currentLoopData = $movies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $movie): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                <div class="card h-100">
                    <?php if($movie->poster_url): ?>
                        <img src="<?php echo e($movie->poster_url); ?>" class="card-img-top" alt="<?php echo e($movie->title); ?>" style="height: 300px; object-fit: cover;">
                    <?php else: ?>
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 300px;">
                            <i class="fas fa-film fa-3x text-muted"></i>
                        </div>
                    <?php endif; ?>
                    
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?php echo e($movie->title); ?></h5>
                        <p class="card-text text-muted small">
                            <i class="fas fa-tag"></i> <?php echo e($movie->genre); ?> | 
                            <i class="fas fa-clock"></i> <?php echo e($movie->duration_in_hours); ?> |
                            <i class="fas fa-star"></i> <?php echo e($movie->rating); ?>

                        </p>
                        <p class="card-text"><?php echo e(Str::limit($movie->description, 100)); ?></p>
                        
                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="h5 text-primary">$<?php echo e(number_format($movie->price, 2)); ?></span>
                                <span class="badge bg-<?php echo e($movie->status == 'now_showing' ? 'success' : ($movie->status == 'coming_soon' ? 'warning' : 'secondary')); ?>">
                                    <?php echo e(ucfirst(str_replace('_', ' ', $movie->status))); ?>

                                </span>
                            </div>
                            
                            <div class="btn-group w-100" role="group">
                                <a href="<?php echo e(route('movies.show', $movie)); ?>" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="<?php echo e(route('movies.edit', $movie)); ?>" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="<?php echo e(route('movies.destroy', $movie)); ?>" method="POST" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-danger btn-sm" 
                                            onclick="return confirm('Are you sure you want to delete this movie?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-film fa-3x text-muted mb-3"></i>
                        <h4>No Movies Found</h4>
                        <p class="text-muted">Start by adding your first movie to the system.</p>
                        <a href="<?php echo e(route('movies.create')); ?>" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add First Movie
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="d-flex justify-content-center">
        <?php echo e($movies->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\si_bioskop\resources\views/admin/movies/index.blade.php ENDPATH**/ ?>