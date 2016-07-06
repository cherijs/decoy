-# Dependencies
-use Bkwld\Decoy\Controllers\Elements
-use Illuminate\Support\Collection

-# Form tag
!=Former::open_vertical_for_files()->addClass('row')

-# Create navigation
.padded-col.tab-sidebar.affixable(data-top="58")

	-if(($locales = Config::get('decoy::site.locales')) && count($locales) > 1)
		%fieldset.locale
			.legend
				Locale
				.btn-group.pull-right
					%button.btn.btn-sm.outline.dropdown-toggle(type="button" data-toggle="dropdown" aria-expanded="false")
						=Config::get('decoy::site.locales')[$locale]
						%spen.caret
					%ul.dropdown-menu(role="menu")
						-foreach($locales as $slug => $label)
							%li(class=$slug==$locale?'disabled':null)
								%a(href=route('decoy::elements', $slug))
									-if($slug==$locale)
										%span.glyphicon.glyphicon-ok
									=$label

	%ul.nav.nav-stacked.nav-pills(role="tablist")
		-$first = 0
		-foreach($elements->groupBy('page_label') as $page => $sections)
			-$slug = Str::slug($page)
			-$path = $locale ? $locale.'/'.$slug : $slug
			-$active = (empty($tab) && $first++==0 ) || $slug == $tab
			%li(class=$active?'active':null)
				%a.js-tooltip(href='#'.$slug 
					data-slug=$path 
					data-toggle="tab" 
					role="tab" 
					title=$sections[0]->page_help 
					data-placement="left")=$page

-# Create pages
.padded-col.tab-content
	-$first = 0
	-foreach($elements->groupBy('page_label') as $page => $sections)
		-$slug = Str::slug($page)
		-$sections = Collection::make($sections)->groupBy('section_label')
		-$active = (empty($tab) && $first++==0 ) || $slug == $tab
		.tab-pane(class=$active?'active':null id=$slug)
			
			-# Create sections
			-foreach($sections as $section => $fields)
				%fieldset
					.legend
						%span.js-tooltip(title=$fields[0]->section_help)=$section

					-# Create pairs
					-foreach($fields as $el)
						!= Elements::renderField($el)

.controls.form-actions
	%button.btn.btn-success.save(type="submit")
		%span.glyphicon.glyphicon-file
		Save all tabs

!=Former::close() 