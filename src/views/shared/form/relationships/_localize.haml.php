-# Require a model to act on
-if($item)
	%fieldset.form-vertical.localize
		.legend Localize

		-# Show the compare interface
		-$localizations = $localize->other()
		-if(!$localizations->isEmpty())
			.form-group.compare
				%label.control-label Compare
				.radio
					%label
						%input(type="radio" name="compare" checked)
						None
				-foreach($localizations as $model)
					.radio
						%label
							%input(type="radio" name="compare")
							%strong=Config::get('decoy::site.locales')[$model->locale]
							=' - '
							%a(href=DecoyURL::relative('edit', $model->getKey()))!=$model->title()
				%p.help-block Choose an existing localization for this <b>#{$title}</b> to compare against.  Rollover form element to view the content in the selected localization.

		-# Create a new localization menu if there are un-assigned locales
		-$locales = $localize->localizableLocales()
		-if(count($locales))
			!=Former::vertical_open(DecoyURL::relative('duplicate', $item->getKey()))
			.form-group.create
				%label.control-label Create

				-# Show a locale select menu or an un-editable text menu if there is only one
				-if (count($locales) > 1)
					%select.form-control(name='locale')
						-foreach($locales as $locale => $label)
							%option(value=$locale) A #{$label} localization
							.check
				-else
					-$label = reset($locales)
					-$locale = key($locales)
					%input(type='hidden' name='locale' value=$locale)
					.form-control A #{$label} localization
				
				-# Additional options
				%input(type="hidden" name='options')
				.checkbox
					%label
						%input(type="checkbox" name='options[]' value='text' checked)
						Include text and settings
				-if(!empty($item->file_attributes))
					.checkbox
						%label
							%input(type="checkbox" name='options[]' value='files' checked)
							Include images and files
					
				-# Help
				%p.help-block Creating a new localization of the current <b>#{$title}</b> will create a copy of <i>this</i> <b>#{$title}</b> using the selected locale.  The new <b>#{$title}</b> will be set to "Hidden".

				-# Submit
				%button.btn.btn-default
					%span.glyphicon.glyphicon-plus.glyphicon
					Create				
			!=Former::close()

		-else
			-# Not possible to localize
			.form-group.create.disabled
				%label.control-label Create
				%p.help-block This <b>#{$title}</b> cannot be further localized because each locale already has a copy.  The links under "Compare" can be used to view them.

-else
	%fieldset.disabled
		.legend Localize
		%p Localized copies of this <b>#{$title}</b> cannot be created until it is saved.