

<?php $__env->startSection('title', 'Showtimes Management'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="mt-4">Showtimes Management</h1>
        <a href="<?php echo e(route('showtimes.create')); ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Showtime
        </a>
    </div>
    
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
        <li class="breadcrumb-item active">Showtimes</li>
    </ol>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-clock me-1"></i>
            Movie Showtimes
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Movie</th>
                            <th>Theater</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Available Seats</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $showtimes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $showtime): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <?php if($showtime->movie->poster_url): ?>
                                            <img src="<?php echo e($showtime->movie->poster_url); ?>" 
                                                 class="rounded me-2" 
                                                 style="width: 40px; height: 60px; object-fit: cover;">
                                        <?php endif; ?>
                                        <div>
                                            <strong><?php echo e($showtime->movie->title); ?></strong>
                                            <br>
                                            <small class="text-muted"><?php echo e($showtime->movie->genre); ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <strong><?php echo e($showtime->theater->name); ?></strong>
                                    <br>
                                    <small class="text-muted">
                                        <?php echo e(ucfirst($showtime->theater->type)); ?> | 
                                        Capacity: <?php echo e($showtime->theater->capacity); ?>

                                    </small>
                                </td>
                                <td><?php echo e($showtime->show_date->format('M d, Y')); ?></td>
                                <td><?php echo e($showtime->show_time->format('H:i')); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo e($showtime->available_seats > 0 ? 'success' : 'danger'); ?>">
                                        <?php echo e($showtime->available_seats); ?> / <?php echo e($showtime->theater->capacity); ?>

                                    </span>
                                </td>
                                <td>$<?php echo e(number_format($showtime->ticket_price, 2)); ?></td>
                                <td>
                                    <?php
                                        $now = now();
                                        $showtimeDateTime = $showtime->show_date->format('Y-m-d') . ' ' . $showtime->show_time->format('H:i:s');
                                        $isUpcoming = $now < $showtimeDateTime;
                                        $isOngoing = $now >= $showtimeDateTime && $now <= date('Y-m-d H:i:s', strtotime($showtimeDateTime . ' +' . $showtime->movie->duration . ' minutes'));
                                        $isEnded = $now > date('Y-m-d H:i:s', strtotime($showtimeDateTime . ' +' . $showtime->movie->duration . ' minutes'));
                                    ?>
                                    
                                    <?php if($isUpcoming): ?>
                                        <span class="badge bg-primary">Upcoming</span>
                                    <?php elseif($isOngoing): ?>
                                        <span class="badge bg-success">Now Playing</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Ended</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="<?php echo e(route('showtimes.show', $showtime)); ?>" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?php echo e(route('showtimes.edit', $showtime)); ?>" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="<?php echo e(route('showtimes.destroy', $showtime)); ?>" method="POST" class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-danger btn-sm" 
                                                    onclick="return confirm('Are you sure you want to delete this showtime?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="fas fa-clock fa-3x text-muted mb-3"></i>
                                    <h5>No Showtimes Found</h5>
                                    <p class="text-muted">Start by creating your first showtime schedule.</p>
                                    <a href="<?php echo e(route('showtimes.create')); ?>" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Add First Showtime
                                    </a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center">
                <?php echo e($showtimes->links()); ?>

            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\si_bioskop\resources\views/admin/showtime/index.blade.php ENDPATH**/ ?>