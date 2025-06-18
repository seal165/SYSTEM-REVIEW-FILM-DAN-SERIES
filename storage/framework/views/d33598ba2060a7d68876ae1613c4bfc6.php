

<?php $__env->startSection('title', 'Add New Showtime'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4">
    <h1 class="mt-4">Add New Showtime</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?php echo e(route('showtimes.index')); ?>">Showtimes</a></li>
        <li class="breadcrumb-item active">Add Showtime</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-clock me-1"></i>
            Showtime Information
        </div>
        <div class="card-body">
            <form action="<?php echo e(route('showtimes.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="movie_id" class="form-label">Movie</label>
                            <select class="form-select <?php $__errorArgs = ['movie_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="movie_id" name="movie_id" required>
                                <option value="">Select Movie</option>
                                <?php $__currentLoopData = $movies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $movie): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($movie->id); ?>" 
                                            data-duration="<?php echo e($movie->duration); ?>"
                                            <?php echo e(old('movie_id') == $movie->id ? 'selected' : ''); ?>>
                                        <?php echo e($movie->title); ?> (<?php echo e($movie->duration_in_hours); ?>)
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['movie_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="theater_id" class="form-label">Theater</label>
                            <select class="form-select <?php $__errorArgs = ['theater_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="theater_id" name="theater_id" required>
                                <option value="">Select Theater</option>
                                <?php $__currentLoopData = $theaters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $theater): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($theater->id); ?>" 
                                            data-capacity="<?php echo e($theater->capacity); ?>"
                                            <?php echo e(old('theater_id') == $theater->id ? 'selected' : ''); ?>>
                                        <?php echo e($theater->name); ?> (<?php echo e(ucfirst($theater->type)); ?> - <?php echo e($theater->capacity); ?> seats)
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['theater_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="show_date" class="form-label">Show Date</label>
                            <input type="date" class="form-control <?php $__errorArgs = ['show_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="show_date" name="show_date" value="<?php echo e(old('show_date')); ?>" 
                                   min="<?php echo e(date('Y-m-d')); ?>" required>
                            <?php $__errorArgs = ['show_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="show_time" class="form-label">Show Time</label>
                            <input type="time" class="form-control <?php $__errorArgs = ['show_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="show_time" name="show_time" value="<?php echo e(old('show_time')); ?>" required>
                            <?php $__errorArgs = ['show_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="ticket_price" class="form-label">Ticket Price ($)</label>
                            <input type="number" class="form-control <?php $__errorArgs = ['ticket_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="ticket_price" name="ticket_price" value="<?php echo e(old('ticket_price')); ?>" 
                                   step="0.01" min="0" required>
                            <?php $__errorArgs = ['ticket_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title">Schedule Preview</h6>
                                <div id="schedule-preview">
                                    <p class="text-muted">Select movie, theater, date and time to see schedule preview</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3 mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Showtime
                    </button>
                    <a href="<?php echo e(route('showtimes.index')); ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Showtimes
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function updateSchedulePreview() {
    const movieSelect = document.getElementById('movie_id');
    const theaterSelect = document.getElementById('theater_id');
    const dateInput = document.getElementById('show_date');
    const timeInput = document.getElementById('show_time');
    const preview = document.getElementById('schedule-preview');
    
    if (movieSelect.value && theaterSelect.value && dateInput.value && timeInput.value) {
        const movieOption = movieSelect.options[movieSelect.selectedIndex];
        const theaterOption = theaterSelect.options[theaterSelect.selectedIndex];
        const duration = movieOption.dataset.duration;
        
        const startTime = timeInput.value;
        const endTime = new Date(`2000-01-01 ${startTime}`);
        endTime.setMinutes(endTime.getMinutes() + parseInt(duration));
        
        preview.innerHTML = `
            <div class="row">
                <div class="col-md-6">
                    <strong>Movie:</strong> ${movieOption.text}<br>
                    <strong>Theater:</strong> ${theaterOption.text}<br>
                    <strong>Date:</strong> ${new Date(dateInput.value).toLocaleDateString()}
                </div>
                <div class="col-md-6">
                    <strong>Start Time:</strong> ${startTime}<br>
                    <strong>End Time:</strong> ${endTime.toTimeString().substr(0,5)}<br>
                    <strong>Duration:</strong> ${duration} minutes
                </div>
            </div>
        `;
    } else {
        preview.innerHTML = '<p class="text-muted">Select movie, theater, date and time to see schedule preview</p>';
    }
}

document.getElementById('movie_id').addEventListener('change', updateSchedulePreview);
document.getElementById('theater_id').addEventListener('change', updateSchedulePreview);
document.getElementById('show_date').addEventListener('change', updateSchedulePreview);
document.getElementById('show_time').addEventListener('change', updateSchedulePreview);
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\si_bioskop\resources\views/admin/showtime/create.blade.php ENDPATH**/ ?>