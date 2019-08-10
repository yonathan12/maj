<form action="<?= base_url();?>menu/editSubmenu" method="POST">
        <div class="form-group">
            <input type="text" class="form-control" id="title" name="title" placeholder="Sub Menu Title" value="<?= $subMenu['title'] ?>">
            <input type="text" hidden="" name="Id" value="<?= $subMenu['Id'] ?>">
        </div>
        <div class="form-group">
            <select name="menu_id" class="form-control">
            <option value="">Select Menu</option>
            <?php foreach ($menu as $m) : ?> 
            <?php if ($m['Id'] == $subMenu['menu_id']) : ?>
            <option value="<?= $m['Id'] ?>" selected ><?= $m['menu'] ?></option>
            <?php else : ?>
            <option value="<?= $m['Id'] ?>"><?= $m['menu'] ?></option>
            <?php endif; ?>            
            <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <input type="text" class="form-control" id="title" name="url" placeholder="Sub Menu URL" value="<?= $subMenu['url'] ?>">
        </div>
        <div class="form-group">
            <input type="text" class="form-control" id="title" name="icon" placeholder="Sub Menu Icon" value="<?= $subMenu['icon'] ?>">
        </div>
        <div class="form-group">
        <div class="form-check">
        <?php if ($subMenu['is_active'] == 1) : ?>
        <input class="form-check-input" type="checkbox" value="1" id="is_active" name="is_active" checked>
        <?php else :  ?>
        <input class="form-check-input" type="checkbox" value="1" id="is_active" name="is_active">
        <?php endif;  ?>            
        <label class="form-check-label" for="is_active">
            Active ?
        </label>
        </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-success">Simpan</button>
      </div>
      </form>