
<div class="container-fluid" style="height:100%;">

	<?php for ($x = 0; $x < ceil(sqrt($arena_size)); $x++) : ?>

		<div class="row" style="height:100%;">

			<?php for ($y = 0; $y < ceil(sqrt($arena_size)); $y++) : ?>

				<?php if (in_array($x . "_" . $y, array_keys($arena_matrix))) : ?>

					<?php if (($x == ceil(sqrt($arena_size) / 2)) && ($y == ceil(sqrt($arena_size) / 2))) : ?>
						<div class="col-sm-1" style="background-color: <?= $arena_matrix[$x . '_' . $y]['colour']; ?>">X</div>
					<?php else : ?>
						<div class="col-sm-1" style="background-color: <?= $arena_matrix[$x . '_' . $y]['colour']; ?>">O</div>
					<?php endif; ?>

				<?php else : ?>

					<div class="col-sm-1">---</div>

				<?php endif; ?>

			<?php endfor; ?>

		</div>

	<?php endfor; ?>

</div>

<div>
	<button id="next_turn" type="button" class="btn btn-success">Next turn</button>
</div>
