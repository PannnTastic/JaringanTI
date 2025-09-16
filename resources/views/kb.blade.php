@include('partials.head')
<!-- Force Light Theme -->
        <style>
            /* Force light theme - override any system dark mode preferences */
            :root {
                color-scheme: light only !important;
            }
            
            * {
                color-scheme: light !important;
            }
            
            html, body {
                background-color: #FDFDFC !important;
                color: #1b1b18 !important;
            }
            
            /* Override all dark mode styles */
            @media (prefers-color-scheme: dark) {
                html, body {
                    background-color: #FDFDFC !important;
                    color: #1b1b18 !important;
                }
                
                .dark\:bg-\[\#0a0a0a\] {
                    background-color: #FDFDFC !important;
                }
                
                .dark\:text-white {
                    color: #1b1b18 !important;
                }
                
                /* Override any other dark theme classes */
                [class*="dark:"]:not([class*="dark:hidden"]) {
                    filter: none !important;
                }
            }
        </style>
        
        <!-- JavaScript to enforce light theme -->
        <script>
            // Force light theme immediately on page load
            (function() {
                // Remove any dark mode classes from html/body
                document.documentElement.classList.remove('dark');
                document.body.classList.remove('dark');
                
                // Override browser's color scheme detection 
                const meta = document.createElement('meta');
                meta.name = 'color-scheme';
                meta.content = 'light only';
                document.head.appendChild(meta);
                
                // Watch for any changes and force back to light
                const observer = new MutationObserver(function(mutations) {
                    mutations.forEach(function(mutation) {
                        if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                            const target = mutation.target;
                            if (target.classList.contains('dark')) {
                                target.classList.remove('dark');
                            }
                        }
                    });
                });
                
                observer.observe(document.documentElement, { attributes: true });
                observer.observe(document.body, { attributes: true });
            })();
        </script>

        <!-- Styles -->
        <style>
            /*! tailwindcss v4.0.14 | MIT License | https://tailwindcss.com */
            @layer theme{:root,:host{--font-sans:ui-sans-serif,system-ui,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji";--font-mono:ui-monospace,SFMono-Regular,Menlo,Monaco,Consolas,"Liberation Mono","Courier New",monospace;--color-green-600:oklch(.627 .194 149.214);--color-gray-900:oklch(.21 .034 264.665);--color-zinc-50:oklch(.985 0 0);--color-zinc-200:oklch(.92 .004 286.32);--color-zinc-400:oklch(.705 .015 286.067);--color-zinc-500:oklch(.552 .016 285.938);--color-zinc-600:oklch(.442 .017 285.786);--color-zinc-700:oklch(.37 .013 285.805);--color-zinc-800:oklch(.274 .006 286.033);--color-zinc-900:oklch(.21 .006 285.885);--color-neutral-100:oklch(.97 0 0);--color-neutral-200:oklch(.922 0 0);--color-neutral-700:oklch(.371 0 0);--color-neutral-800:oklch(.269 0 0);--color-neutral-900:oklch(.205 0 0);--color-neutral-950:oklch(.145 0 0);--color-stone-800:oklch(.268 .007 34.298);--color-stone-950:oklch(.147 .004 49.25);--color-black:#000;--color-white:#fff;--spacing:.25rem;--container-sm:24rem;--container-md:28rem;--container-lg:32rem;--container-4xl:56rem;--text-xs:.75rem;--text-xs--line-height:calc(1/.75);--text-sm:.875rem;--text-sm--line-height:calc(1.25/.875);--text-lg:1.125rem;--text-lg--line-height:calc(1.75/1.125);--font-weight-normal:400;--font-weight-medium:500;--font-weight-semibold:600;--leading-tight:1.25;--leading-normal:1.5;--radius-sm:.25rem;--radius-md:.375rem;--radius-lg:.5rem;--radius-xl:.75rem;--aspect-video:16/9;--default-transition-duration:.15s;--default-transition-timing-function:cubic-bezier(.4,0,.2,1);--default-font-family:var(--font-sans);--default-font-feature-settings:var(--font-sans--font-feature-settings);--default-font-variation-settings:var(--font-sans--font-variation-settings);--default-mono-font-family:var(--font-mono);--default-mono-font-feature-settings:var(--font-mono--font-feature-settings);--default-mono-font-variation-settings:var(--font-mono--font-variation-settings)}}@layer base{*,:after,:before,::backdrop{box-sizing:border-box;border:0 solid;margin:0;padding:0}::file-selector-button{box-sizing:border-box;border:0 solid;margin:0;padding:0}html,:host{-webkit-text-size-adjust:100%;tab-size:4;line-height:1.5;font-family:var(--default-font-family,ui-sans-serif,system-ui,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji");font-feature-settings:var(--default-font-feature-settings,normal);font-variation-settings:var(--default-font-variation-settings,normal);-webkit-tap-highlight-color:transparent}body{line-height:inherit}hr{height:0;color:inherit;border-top-width:1px}abbr:where([title]){-webkit-text-decoration:underline dotted;text-decoration:underline dotted}h1,h2,h3,h4,h5,h6{font-size:inherit;font-weight:inherit}a{color:inherit;-webkit-text-decoration:inherit;-webkit-text-decoration:inherit;-webkit-text-decoration:inherit;text-decoration:inherit}b,strong{font-weight:bolder}code,kbd,samp,pre{font-family:var(--default-mono-font-family,ui-monospace,SFMono-Regular,Menlo,Monaco,Consolas,"Liberation Mono","Courier New",monospace);font-feature-settings:var(--default-mono-font-feature-settings,normal);font-variation-settings:var(--default-mono-font-variation-settings,normal);font-size:1em}small{font-size:80%}sub,sup{vertical-align:baseline;font-size:75%;line-height:0;position:relative}sub{bottom:-.25em}sup{top:-.5em}table{text-indent:0;border-color:inherit;border-collapse:collapse}:-moz-focusring{outline:auto}progress{vertical-align:baseline}summary{display:list-item}ol,ul,menu{list-style:none}img,svg,video,canvas,audio,iframe,embed,object{vertical-align:middle;display:block}img,video{max-width:100%;height:auto}button,input,select,optgroup,textarea{font:inherit;font-feature-settings:inherit;font-variation-settings:inherit;letter-spacing:inherit;color:inherit;opacity:1;background-color:#0000;border-radius:0}::file-selector-button{font:inherit;font-feature-settings:inherit;font-variation-settings:inherit;letter-spacing:inherit;color:inherit;opacity:1;background-color:#0000;border-radius:0}:where(select:is([multiple],[size])) optgroup{font-weight:bolder}:where(select:is([multiple],[size])) optgroup option{padding-inline-start:20px}::file-selector-button{margin-inline-end:4px}::placeholder{opacity:1;color:color-mix(in oklab,currentColor 50%,transparent)}textarea{resize:vertical}::-webkit-search-decoration{-webkit-appearance:none}::-webkit-date-and-time-value{min-height:1lh;text-align:inherit}::-webkit-datetime-edit{display:inline-flex}::-webkit-datetime-edit-fields-wrapper{padding:0}::-webkit-datetime-edit{padding-block:0}::-webkit-datetime-edit-year-field{padding-block:0}::-webkit-datetime-edit-month-field{padding-block:0}::-webkit-datetime-edit-day-field{padding-block:0}::-webkit-datetime-edit-hour-field{padding-block:0}::-webkit-datetime-edit-minute-field{padding-block:0}::-webkit-datetime-edit-second-field{padding-block:0}::-webkit-datetime-edit-millisecond-field{padding-block:0}::-webkit-datetime-edit-meridiem-field{padding-block:0}:-moz-ui-invalid{box-shadow:none}button,input:where([type=button],[type=reset],[type=submit]){appearance:button}::file-selector-button{appearance:button}::-webkit-inner-spin-button{height:auto}::-webkit-outer-spin-button{height:auto}[hidden]:where(:not([hidden=until-found])){display:none!important}}@layer components;@layer utilities{.sr-only{clip:rect(0,0,0,0);white-space:nowrap;border-width:0;width:1px;height:1px;margin:-1px;padding:0;position:absolute;overflow:hidden}.absolute{position:absolute}.relative{position:relative}.static{position:static}.sticky{position:sticky}.inset-0{inset:calc(var(--spacing)*0)}.inset-y-\[3px\]{inset-block:3px}.start-0{inset-inline-start:calc(var(--spacing)*0)}.end-0{inset-inline-end:calc(var(--spacing)*0)}.top-0{top:calc(var(--spacing)*0)}.z-20{z-index:20}.container{width:100%}@media (width>=40rem){.container{max-width:40rem}}@media (width>=48rem){.container{max-width:48rem}}@media (width>=64rem){.container{max-width:64rem}}@media (width>=80rem){.container{max-width:80rem}}@media (width>=96rem){.container{max-width:96rem}}.mx-auto{margin-inline:auto}.my-6{margin-block:calc(var(--spacing)*6)}.-ms-8{margin-inline-start:calc(var(--spacing)*-8)}.ms-1{margin-inline-start:calc(var(--spacing)*1)}.ms-2{margin-inline-start:calc(var(--spacing)*2)}.ms-4{margin-inline-start:calc(var(--spacing)*4)}.me-1\.5{margin-inline-end:calc(var(--spacing)*1.5)}.me-2{margin-inline-end:calc(var(--spacing)*2)}.me-3{margin-inline-end:calc(var(--spacing)*3)}.me-5{margin-inline-end:calc(var(--spacing)*5)}.me-10{margin-inline-end:calc(var(--spacing)*10)}.-mt-\[4\.9rem\]{margin-top:-4.9rem}.mt-2{margin-top:calc(var(--spacing)*2)}.mt-4{margin-top:calc(var(--spacing)*4)}.mt-5{margin-top:calc(var(--spacing)*5)}.mt-6{margin-top:calc(var(--spacing)*6)}.mt-10{margin-top:calc(var(--spacing)*10)}.mt-auto{margin-top:auto}.-mb-px{margin-bottom:-1px}.mb-0\.5{margin-bottom:calc(var(--spacing)*.5)}.mb-1{margin-bottom:calc(var(--spacing)*1)}.mb-2{margin-bottom:calc(var(--spacing)*2)}.mb-4{margin-bottom:calc(var(--spacing)*4)}.mb-5{margin-bottom:calc(var(--spacing)*5)}.mb-6{margin-bottom:calc(var(--spacing)*6)}.mb-\[2px\]{margin-bottom:2px}.block{display:block}.contents{display:contents}.flex{display:flex}.grid{display:grid}.hidden{display:none}.inline-block{display:inline-block}.inline-flex{display:inline-flex}.table{display:table}.aspect-\[335\/376\]{aspect-ratio:335/376}.aspect-square{aspect-ratio:1}.aspect-video{aspect-ratio:var(--aspect-video)}.size-3\!{width:calc(var(--spacing)*3)!important;height:calc(var(--spacing)*3)!important}.size-5{width:calc(var(--spacing)*5);height:calc(var(--spacing)*5)}.size-8{width:calc(var(--spacing)*8);height:calc(var(--spacing)*8)}.size-9{width:calc(var(--spacing)*9);height:calc(var(--spacing)*9)}.size-full{width:100%;height:100%}.\!h-10{height:calc(var(--spacing)*10)!important}.h-1\.5{height:calc(var(--spacing)*1.5)}.h-2\.5{height:calc(var(--spacing)*2.5)}.h-3\.5{height:calc(var(--spacing)*3.5)}.h-7{height:calc(var(--spacing)*7)}.h-8{height:calc(var(--spacing)*8)}.h-9{height:calc(var(--spacing)*9)}.h-10{height:calc(var(--spacing)*10)}.h-14\.5{height:calc(var(--spacing)*14.5)}.h-dvh{height:100dvh}.h-full{height:100%}.min-h-screen{min-height:100vh}.min-h-svh{min-height:100svh}.w-1\.5{width:calc(var(--spacing)*1.5)}.w-2\.5{width:calc(var(--spacing)*2.5)}.w-3\.5{width:calc(var(--spacing)*3.5)}.w-8{width:calc(var(--spacing)*8)}.w-9{width:calc(var(--spacing)*9)}.w-10{width:calc(var(--spacing)*10)}.w-\[220px\]{width:220px}.w-\[448px\]{width:448px}.w-full{width:100%}.w-px{width:1px}.max-w-\[335px\]{max-width:335px}.max-w-lg{max-width:var(--container-lg)}.max-w-md{max-width:var(--container-md)}.max-w-none{max-width:none}.max-w-sm{max-width:var(--container-sm)}.flex-1{flex:1}.shrink-0{flex-shrink:0}.translate-y-0{--tw-translate-y:calc(var(--spacing)*0);translate:var(--tw-translate-x)var(--tw-translate-y)}.cursor-pointer{cursor:pointer}.auto-rows-min{grid-auto-rows:min-content}.flex-col{flex-direction:column}.flex-col-reverse{flex-direction:column-reverse}.items-center{align-items:center}.items-start{align-items:flex-start}.justify-between{justify-content:space-between}.justify-center{justify-content:center}.justify-end{justify-content:flex-end}.gap-2{gap:calc(var(--spacing)*2)}.gap-3{gap:calc(var(--spacing)*3)}.gap-4{gap:calc(var(--spacing)*4)}.gap-6{gap:calc(var(--spacing)*6)}:where(.space-y-2>:not(:last-child)){--tw-space-y-reverse:0;margin-block-start:calc(calc(var(--spacing)*2)*var(--tw-space-y-reverse));margin-block-end:calc(calc(var(--spacing)*2)*calc(1 - var(--tw-space-y-reverse)))}:where(.space-y-3>:not(:last-child)){--tw-space-y-reverse:0;margin-block-start:calc(calc(var(--spacing)*3)*var(--tw-space-y-reverse));margin-block-end:calc(calc(var(--spacing)*3)*calc(1 - var(--tw-space-y-reverse)))}:where(.space-y-6>:not(:last-child)){--tw-space-y-reverse:0;margin-block-start:calc(calc(var(--spacing)*6)*var(--tw-space-y-reverse));margin-block-end:calc(calc(var(--spacing)*6)*calc(1 - var(--tw-space-y-reverse)))}:where(.space-y-\[2px\]>:not(:last-child)){--tw-space-y-reverse:0;margin-block-start:calc(2px*var(--tw-space-y-reverse));margin-block-end:calc(2px*calc(1 - var(--tw-space-y-reverse)))}:where(.space-x-0\.5>:not(:last-child)){--tw-space-x-reverse:0;margin-inline-start:calc(calc(var(--spacing)*.5)*var(--tw-space-x-reverse));margin-inline-end:calc(calc(var(--spacing)*.5)*calc(1 - var(--tw-space-x-reverse)))}:where(.space-x-1>:not(:last-child)){--tw-space-x-reverse:0;margin-inline-start:calc(calc(var(--spacing)*1)*var(--tw-space-x-reverse));margin-inline-end:calc(calc(var(--spacing)*1)*calc(1 - var(--tw-space-x-reverse)))}:where(.space-x-2>:not(:last-child)){--tw-space-x-reverse:0;margin-inline-start:calc(calc(var(--spacing)*2)*var(--tw-space-x-reverse));margin-inline-end:calc(calc(var(--spacing)*2)*calc(1 - var(--tw-space-x-reverse)))}.self-stretch{align-self:stretch}.truncate{text-overflow:ellipsis;white-space:nowrap;overflow:hidden}.overflow-hidden{overflow:hidden}.rounded-full{border-radius:3.40282e38px}.rounded-lg{border-radius:var(--radius-lg)}.rounded-md{border-radius:var(--radius-md)}.rounded-sm{border-radius:var(--radius-sm)}.rounded-xl{border-radius:var(--radius-xl)}.rounded-ee-lg{border-end-end-radius:var(--radius-lg)}.rounded-es-lg{border-end-start-radius:var(--radius-lg)}.rounded-t-lg{border-top-left-radius:var(--radius-lg);border-top-right-radius:var(--radius-lg)}.border{border-style:var(--tw-border-style);border-width:1px}.border-r{border-right-style:var(--tw-border-style);border-right-width:1px}.border-b{border-bottom-style:var(--tw-border-style);border-bottom-width:1px}.border-\[\#19140035\]{border-color:#19140035}.border-\[\#e3e3e0\]{border-color:#e3e3e0}.border-black{border-color:var(--color-black)}.border-neutral-200{border-color:var(--color-neutral-200)}.border-transparent{border-color:#0000}.border-zinc-200{border-color:var(--color-zinc-200)}.bg-\[\#1b1b18\]{background-color:#1b1b18}.bg-\[\#FDFDFC\]{background-color:#fdfdfc}.bg-\[\#dbdbd7\]{background-color:#dbdbd7}.bg-\[\#fff2f2\]{background-color:#fff2f2}.bg-neutral-100{background-color:var(--color-neutral-100)}.bg-neutral-200{background-color:var(--color-neutral-200)}.bg-neutral-900{background-color:var(--color-neutral-900)}.bg-white{background-color:var(--color-white)}.bg-zinc-50{background-color:var(--color-zinc-50)}.bg-zinc-200{background-color:var(--color-zinc-200)}.fill-current{fill:currentColor}.stroke-gray-900\/20{stroke:color-mix(in oklab,var(--color-gray-900)20%,transparent)}.p-0{padding:calc(var(--spacing)*0)}.p-6{padding:calc(var(--spacing)*6)}.p-10{padding:calc(var(--spacing)*10)}.px-1{padding-inline:calc(var(--spacing)*1)}.px-5{padding-inline:calc(var(--spacing)*5)}.px-8{padding-inline:calc(var(--spacing)*8)}.px-10{padding-inline:calc(var(--spacing)*10)}.py-0\!{padding-block:calc(var(--spacing)*0)!important}.py-1{padding-block:calc(var(--spacing)*1)}.py-1\.5{padding-block:calc(var(--spacing)*1.5)}.py-2{padding-block:calc(var(--spacing)*2)}.py-8{padding-block:calc(var(--spacing)*8)}.ps-3{padding-inline-start:calc(var(--spacing)*3)}.ps-7{padding-inline-start:calc(var(--spacing)*7)}.pe-4{padding-inline-end:calc(var(--spacing)*4)}.pb-4{padding-bottom:calc(var(--spacing)*4)}.pb-12{padding-bottom:calc(var(--spacing)*12)}.text-center{text-align:center}.text-start{text-align:start}.text-lg{font-size:var(--text-lg);line-height:var(--tw-leading,var(--text-lg--line-height))}.text-sm{font-size:var(--text-sm);line-height:var(--tw-leading,var(--text-sm--line-height))}.text-xs{font-size:var(--text-xs);line-height:var(--tw-leading,var(--text-xs--line-height))}.text-\[13px\]{font-size:13px}.leading-\[20px\]{--tw-leading:20px;line-height:20px}.leading-none{--tw-leading:1;line-height:1}.leading-normal{--tw-leading:var(--leading-normal);line-height:var(--leading-normal)}.leading-tight{--tw-leading:var(--leading-tight);line-height:var(--leading-tight)}.font-medium{--tw-font-weight:var(--font-weight-medium);font-weight:var(--font-weight-medium)}.font-normal{--tw-font-weight:var(--font-weight-normal);font-weight:var(--font-weight-normal)}.font-semibold{--tw-font-weight:var(--font-weight-semibold);font-weight:var(--font-weight-semibold)}.\!text-green-600{color:var(--color-green-600)!important}.text-\[\#1b1b18\]{color:#1b1b18}.text-\[\#706f6c\]{color:#706f6c}.text-\[\#F53003\],.text-\[\#f53003\]{color:#f53003}.text-black{color:var(--color-black)}.text-green-600{color:var(--color-green-600)}.text-stone-800{color:var(--color-stone-800)}.text-white{color:var(--color-white)}.text-zinc-400{color:var(--color-zinc-400)}.text-zinc-500{color:var(--color-zinc-500)}.text-zinc-600{color:var(--color-zinc-600)}.lowercase{text-transform:lowercase}.underline{text-decoration-line:underline}.underline-offset-4{text-underline-offset:4px}.antialiased{-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}.opacity-100{opacity:1}.shadow-\[0px_0px_1px_0px_rgba\(0\,0\,0\,0\.03\)\,0px_1px_2px_0px_rgba\(0\,0\,0\,0\.06\)\]{--tw-shadow:0px 0px 1px 0px var(--tw-shadow-color,#00000008),0px 1px 2px 0px var(--tw-shadow-color,#0000000f);box-shadow:var(--tw-inset-shadow),var(--tw-inset-ring-shadow),var(--tw-ring-offset-shadow),var(--tw-ring-shadow),var(--tw-shadow)}.shadow-\[inset_0px_0px_0px_1px_rgba\(26\,26\,0\,0\.16\)\]{--tw-shadow:inset 0px 0px 0px 1px var(--tw-shadow-color,#1a1a0029);box-shadow:var(--tw-inset-shadow),var(--tw-inset-ring-shadow),var(--tw-ring-offset-shadow),var(--tw-ring-shadow),var(--tw-shadow)}.shadow-xs{--tw-shadow:0 1px 2px 0 var(--tw-shadow-color,#0000000d);box-shadow:var(--tw-inset-shadow),var(--tw-inset-ring-shadow),var(--tw-ring-offset-shadow),var(--tw-ring-shadow),var(--tw-shadow)}.outline{outline-style:var(--tw-outline-style);outline-width:1px}.transition-all{transition-property:all;transition-timing-function:var(--tw-ease,var(--default-transition-timing-function));transition-duration:var(--tw-duration,var(--default-transition-duration))}.transition-opacity{transition-property:opacity;transition-timing-function:var(--tw-ease,var(--default-transition-timing-function));transition-duration:var(--tw-duration,var(--default-transition-duration))}.delay-300{transition-delay:.3s}.duration-750{--tw-duration:.75s;transition-duration:.75s}.not-has-\[nav\]\:hidden:not(:has(:is(nav))){display:none}.group-data-open\/disclosure-button\:block:is(:where(.group\/disclosure-button)[data-open] *){display:block}.group-data-open\/disclosure-button\:hidden:is(:where(.group\/disclosure-button)[data-open] *){display:none}.before\:absolute:before{content:var(--tw-content);position:absolute}.before\:start-\[0\.4rem\]:before{content:var(--tw-content);inset-inline-start:.4rem}.before\:top-0:before{content:var(--tw-content);top:calc(var(--spacing)*0)}.before\:top-1\/2:before{content:var(--tw-content);top:50%}.before\:bottom-0:before{content:var(--tw-content);bottom:calc(var(--spacing)*0)}.before\:bottom-1\/2:before{content:var(--tw-content);bottom:50%}.before\:left-\[0\.4rem\]:before{content:var(--tw-content);left:.4rem}.before\:border-l:before{content:var(--tw-content);border-left-style:var(--tw-border-style);border-left-width:1px}.before\:border-\[\#e3e3e0\]:before{content:var(--tw-content);border-color:#e3e3e0}@media (hover:hover){.hover\:border-\[\#1915014a\]:hover{border-color:#1915014a}.hover\:border-\[\#19140035\]:hover{border-color:#19140035}.hover\:border-black:hover{border-color:var(--color-black)}.hover\:bg-black:hover{background-color:var(--color-black)}.hover\:bg-zinc-800\/5:hover{background-color:color-mix(in oklab,var(--color-zinc-800)5%,transparent)}.hover\:text-zinc-800:hover{color:var(--color-zinc-800)}}.data-open\:block[data-open]{display:block}@media (width<64rem){.max-lg\:hidden{display:none}}@media (width<48rem){.max-md\:flex-col{flex-direction:column}.max-md\:pt-6{padding-top:calc(var(--spacing)*6)}}@media (width>=40rem){.sm\:w-\[350px\]{width:350px}.sm\:px-0{padding-inline:calc(var(--spacing)*0)}}@media (width>=48rem){.md\:hidden{display:none}.md\:w-\[220px\]{width:220px}.md\:grid-cols-3{grid-template-columns:repeat(3,minmax(0,1fr))}.md\:p-10{padding:calc(var(--spacing)*10)}}@media (width>=64rem){.lg\:-ms-px{margin-inline-start:-1px}.lg\:ms-0{margin-inline-start:calc(var(--spacing)*0)}.lg\:-mt-\[6\.6rem\]{margin-top:-6.6rem}.lg\:mb-0{margin-bottom:calc(var(--spacing)*0)}.lg\:mb-6{margin-bottom:calc(var(--spacing)*6)}.lg\:block{display:block}.lg\:flex{display:flex}.lg\:hidden{display:none}.lg\:aspect-auto{aspect-ratio:auto}.lg\:h-8{height:calc(var(--spacing)*8)}.lg\:w-\[438px\]{width:438px}.lg\:max-w-4xl{max-width:var(--container-4xl)}.lg\:max-w-none{max-width:none}.lg\:grow{flex-grow:1}.lg\:grid-cols-2{grid-template-columns:repeat(2,minmax(0,1fr))}.lg\:flex-row{flex-direction:row}.lg\:justify-center{justify-content:center}.lg\:rounded-ss-lg{border-start-start-radius:var(--radius-lg)}.lg\:rounded-e-lg{border-start-end-radius:var(--radius-lg);border-end-end-radius:var(--radius-lg)}.lg\:rounded-e-lg\!{border-start-end-radius:var(--radius-lg)!important;border-end-end-radius:var(--radius-lg)!important}.lg\:rounded-ee-none{border-end-end-radius:0}.lg\:rounded-t-none{border-top-left-radius:0;border-top-right-radius:0}.lg\:p-8{padding:calc(var(--spacing)*8)}.lg\:p-20{padding:calc(var(--spacing)*20)}.lg\:px-0{padding-inline:calc(var(--spacing)*0)}}:where(.rtl\:space-x-reverse:where(:dir(rtl),[dir=rtl],[dir=rtl] *)>:not(:last-child)){--tw-space-x-reverse:1}@media (prefers-color-scheme:dark){.dark\:block{display:block}.dark\:hidden{display:none}.dark\:border-r{border-right-style:var(--tw-border-style);border-right-width:1px}.dark\:border-\[\#3E3E3A\]{border-color:#3e3e3a}.dark\:border-\[\#eeeeec\]{border-color:#eeeeec}.dark\:border-neutral-700{border-color:var(--color-neutral-700)}.dark\:border-neutral-800{border-color:var(--color-neutral-800)}.dark\:border-stone-800{border-color:var(--color-stone-800)}.dark\:border-zinc-700{border-color:var(--color-zinc-700)}.dark\:bg-\[\#0a0a0a\]{background-color:#0a0a0a}.dark\:bg-\[\#1D0002\]{background-color:#1d0002}.dark\:bg-\[\#3E3E3A\]{background-color:#3e3e3a}.dark\:bg-\[\#161615\]{background-color:#161615}.dark\:bg-\[\#eeeeec\]{background-color:#eeeeec}.dark\:bg-neutral-700{background-color:var(--color-neutral-700)}.dark\:bg-stone-950{background-color:var(--color-stone-950)}.dark\:bg-white\/30{background-color:color-mix(in oklab,var(--color-white)30%,transparent)}.dark\:bg-zinc-800{background-color:var(--color-zinc-800)}.dark\:bg-zinc-900{background-color:var(--color-zinc-900)}.dark\:bg-linear-to-b{--tw-gradient-position:to bottom in oklab;background-image:linear-gradient(var(--tw-gradient-stops))}.dark\:from-neutral-950{--tw-gradient-from:var(--color-neutral-950);--tw-gradient-stops:var(--tw-gradient-via-stops,var(--tw-gradient-position),var(--tw-gradient-from)var(--tw-gradient-from-position),var(--tw-gradient-to)var(--tw-gradient-to-position))}.dark\:to-neutral-900{--tw-gradient-to:var(--color-neutral-900);--tw-gradient-stops:var(--tw-gradient-via-stops,var(--tw-gradient-position),var(--tw-gradient-from)var(--tw-gradient-from-position),var(--tw-gradient-to)var(--tw-gradient-to-position))}.dark\:stroke-neutral-100\/20{stroke:color-mix(in oklab,var(--color-neutral-100)20%,transparent)}.dark\:text-\[\#1C1C1A\]{color:#1c1c1a}.dark\:text-\[\#A1A09A\]{color:#a1a09a}.dark\:text-\[\#EDEDEC\]{color:#ededec}.dark\:text-\[\#F61500\]{color:#f61500}.dark\:text-\[\#FF4433\]{color:#f43}.dark\:text-black{color:var(--color-black)}.dark\:text-white{color:var(--color-white)}.dark\:text-white\/80{color:color-mix(in oklab,var(--color-white)80%,transparent)}.dark\:text-zinc-400{color:var(--color-zinc-400)}.dark\:shadow-\[inset_0px_0px_0px_1px_\#fffaed2d\]{--tw-shadow:inset 0px 0px 0px 1px var(--tw-shadow-color,#fffaed2d);box-shadow:var(--tw-inset-shadow),var(--tw-inset-ring-shadow),var(--tw-ring-offset-shadow),var(--tw-ring-shadow),var(--tw-shadow)}.dark\:before\:border-\[\#3E3E3A\]:before{content:var(--tw-content);border-color:#3e3e3a}@media (hover:hover){.dark\:hover\:border-\[\#3E3E3A\]:hover{border-color:#3e3e3a}.dark\:hover\:border-\[\#62605b\]:hover{border-color:#62605b}.dark\:hover\:border-white:hover{border-color:var(--color-white)}.dark\:hover\:bg-white:hover{background-color:var(--color-white)}.dark\:hover\:bg-white\/\[7\%\]:hover{background-color:color-mix(in oklab,var(--color-white)7%,transparent)}.dark\:hover\:text-white:hover{color:var(--color-white)}}}@starting-style{.starting\:translate-y-4{--tw-translate-y:calc(var(--spacing)*4);translate:var(--tw-translate-x)var(--tw-translate-y)}}@starting-style{.starting\:translate-y-6{--tw-translate-y:calc(var(--spacing)*6);translate:var(--tw-translate-x)var(--tw-translate-y)}}@starting-style{.starting\:opacity-0{opacity:0}}.\[\&\>div\>svg\]\:size-5>div>svg{width:calc(var(--spacing)*5);height:calc(var(--spacing)*5)}:where(.\[\:where\(\&\)\]\:size-4){width:calc(var(--spacing)*4);height:calc(var(--spacing)*4)}:where(.\[\:where\(\&\)\]\:size-5){width:calc(var(--spacing)*5);height:calc(var(--spacing)*5)}:where(.\[\:where\(\&\)\]\:size-6){width:calc(var(--spacing)*6);height:calc(var(--spacing)*6)}}@property --tw-translate-x{syntax:"*";inherits:false;initial-value:0}@property --tw-translate-y{syntax:"*";inherits:false;initial-value:0}@property --tw-translate-z{syntax:"*";inherits:false;initial-value:0}@property --tw-space-y-reverse{syntax:"*";inherits:false;initial-value:0}@property --tw-space-x-reverse{syntax:"*";inherits:false;initial-value:0}@property --tw-border-style{syntax:"*";inherits:false;initial-value:solid}@property --tw-leading{syntax:"*";inherits:false}@property --tw-font-weight{syntax:"*";inherits:false}@property --tw-shadow{syntax:"*";inherits:false;initial-value:0 0 #0000}@property --tw-shadow-color{syntax:"*";inherits:false}@property --tw-inset-shadow{syntax:"*";inherits:false;initial-value:0 0 #0000}@property --tw-inset-shadow-color{syntax:"*";inherits:false}@property --tw-ring-color{syntax:"*";inherits:false}@property --tw-ring-shadow{syntax:"*";inherits:false;initial-value:0 0 #0000}@property --tw-inset-ring-color{syntax:"*";inherits:false}@property --tw-inset-ring-shadow{syntax:"*";inherits:false;initial-value:0 0 #0000}@property --tw-ring-inset{syntax:"*";inherits:false}@property --tw-ring-offset-width{syntax:"<length>";inherits:false;initial-value:0}@property --tw-ring-offset-color{syntax:"*";inherits:false;initial-value:#fff}@property --tw-ring-offset-shadow{syntax:"*";inherits:false;initial-value:0 0 #0000}@property --tw-outline-style{syntax:"*";inherits:false;initial-value:solid}@property --tw-duration{syntax:"*";inherits:false}@property --tw-content{syntax:"*";inherits:false;initial-value:""}@property --tw-gradient-position{syntax:"*";inherits:false}@property --tw-gradient-from{syntax:"<color>";inherits:false;initial-value:#0000}@property --tw-gradient-via{syntax:"<color>";inherits:false;initial-value:#0000}@property --tw-gradient-to{syntax:"<color>";inherits:false;initial-value:#0000}@property --tw-gradient-stops{syntax:"*";inherits:false}@property --tw-gradient-via-stops{syntax:"*";inherits:false}@property --tw-gradient-from-position{syntax:"<length-percentage>";inherits:false;initial-value:0%}@property --tw-gradient-via-position{syntax:"<length-percentage>";inherits:false;initial-value:50%}@property --tw-gradient-to-position{syntax:"<length-percentage>";inherits:false;initial-value:100%}
        </style>
    
    <body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex lg:justify-center min-h-screen flex-col">
    <h1><!DOCTYPE html>
<html lang="id" class="">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Knowledge Base - {{ config('app.name') }}</title>
    
     <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50 ">
    <!-- Header -->
<style>
        body { 
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            

        }
        
        /* Glassmorphism effect */
        .glass-header {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        
        /* Animated gradient text */
        .gradient-text {
            background: linear-gradient(135deg, #1e3a8a, #3b82f6, #6366f1, #8b5cf6);
            background-size: 300% 300%;
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: gradientShift 4s ease-in-out infinite;
        }
        
        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        
        /* Modern button with enhanced effects */
        .btn-modern {
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, #3b82f6, #1e40af);
            border: none;
            padding: 12px 24px;
            border-radius: 12px;
            color: white;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 4px 20px rgba(59, 130, 246, 0.3);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }
        
        .btn-modern:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(59, 130, 246, 0.5);
            background: linear-gradient(135deg, #2563eb, #1e3a8a);
            color: white;
        }
        
        /* Shimmer effect on hover */
        .btn-modern::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: all 0.6s;
        }
        
        .btn-modern:hover::before {
            left: 100%;
        }
        
        /* Logo animation */
        .logo-container {
            transition: all 0.3s ease;
        }
        
        .logo-container:hover {
            transform: scale(1.05);
        }
        
        .logo-glow {
            filter: drop-shadow(0 0 8px rgba(59, 130, 246, 0.3));
            transition: filter 0.3s ease;
        }
        
        .logo-container:hover .logo-glow {
            filter: drop-shadow(0 0 16px rgba(59, 130, 246, 0.6));
        }
        
        /* Smooth scroll behavior */
        html {
            scroll-behavior: smooth;
        }
        
        /* Header fixed positioning with smooth show/hide */
        .header-fixed {
            position: fixed;
            position: sticky;
            top: 0;
            left: 0;
            right: 0;
            z-index: 50;
            transition: transform 0.3s ease-in-out;
        }
        
        .header-hidden {
            transform: translateY(-100%);
        }
        
        /* Mobile menu styles */
        .mobile-menu {
            transform: translateX(100%);
            transition: transform 0.3s ease-in-out;
        }
        
        .mobile-menu.active {
            transform: translateX(0);
        }
        
        /* Icon animations */
        .icon-spin {
            transition: transform 0.3s ease;
        }
        
        .icon-spin:hover {
            transform: rotate(360deg);
        }
        
        /* Responsive improvements */
        @media (max-width: 768px) {
            .btn-modern {
                padding: 10px 20px;
                font-size: 14px;
            }
            
            .gradient-text {
                font-size: 1.5rem;
            }
        }
        
        /* Custom animations */
        @keyframes slideInDown {
            from {
                transform: translateY(-30px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        .slide-in {
            animation: slideInDown 0.6s ease-out;
        }
        
        /* Notification badge */
        
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        
    </style>
</head>

<body class="bg-gray-50 pt-20 ">
    <!-- Enhanced Header -->
    <header class="glass-header header-fixed header-sticky slide-in">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <!-- Logo Section with enhanced styling -->
                <div class="flex items-center space-x-4 logo-container">
                    <div class="relative">
                        <!-- Using a placeholder div for the logo since we can't access the actual image -->
                        {{-- <div class="w-12 h-9 bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 rounded-lg flex items-center justify-center logo-glow">
                            <i class="fas fa-bolt text-white text-xl"></i>
                        </div> --}}
                        <!-- You can replace the div above with your actual logo: -->
                        <img src="/img/pln batam low res (3).png" alt="PLN Batam Logo" class="w-30 h-9 logo-glow">
                    </div>
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold gradient-text">
                            Jaringan TI
                        </h1>
                        <p class="text-xs text-gray-600 font-medium hidden sm:block">
                            PLN Batam Digital Solutions
                        </p>
                    </div>
                </div>
                
                <!-- Navigation Section -->
                <div class="flex items-center space-x-6">
                   
                    <a href="/user" class="btn-modern">
                        <i class="fas fa-users mr-2"></i>
                        <span class="hidden sm:inline">Profil</span>
                        <span class="sm:hidden">Profil</span>
                    </a>
                    <a href="/admin" class="btn-modern">
                        <i class="fas fa-user mr-2"></i>
                        <span class="hidden sm:inline">Login JARTI</span>
                        <span class="sm:hidden">Login</span>
                    </a>
                    
                    <!-- Mobile Menu Button -->
                    <button id="mobileMenuBtn" class="md:hidden p-2 text-gray-600 hover:text-blue-600 transition-colors">
                        <i class="fas fa-bars text-lg"></i>
                    </button>
                </div>
            </div>
            
            <!-- Mobile Quick Search (Hidden by default) -->
            <div id="mobileSearch" class="md:hidden border-t border-gray-200 py-3 hidden">
                <div class="flex items-center space-x-3">
                    <div class="flex-1 relative">
                        <input type="text" 
                               placeholder="Cari knowledge base..." 
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                    <button class="btn-modern py-2 px-4">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Progress bar for scroll -->
        <div class="absolute bottom-0 left-0 h-1 bg-gradient-to-r from-blue-500 to-purple-600 transition-all duration-300" 
             id="scrollProgress" style="width: 0%;"></div>
    </header>
    
    <!-- Mobile Side Menu -->
    <div id="mobileMenu" class="mobile-menu fixed top-0 right-0 h-full w-80 bg-white shadow-2xl z-50 md:hidden">
        <div class="p-6">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-xl font-bold text-gray-800">Menu</h2>
                <button id="closeMobileMenu" class="p-2 text-gray-600 hover:text-gray-800">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Hero Section 3D Dynamic -->
<!-- Enhanced Hero Section -->
<section id="hero3d" 
    class="relative flex flex-col items-center justify-center text-center 
           px-6 py-10 overflow-hidden perspective-[1200px]
           bg-gradient-to-br from-blue-900 via-blue-700 to-indigo-800">

    <!-- Animated Background Particles -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="particle particle-1"></div>
        <div class="particle particle-2"></div>
        <div class="particle particle-3"></div>
        <div class="particle particle-4"></div>
        <div class="particle particle-5"></div>
        <div class="particle particle-6"></div>
    </div>

    <!-- Floating Geometric Shapes -->
    <div class="absolute inset-0 pointer-events-none">
        <div class="floating-shape shape-1"></div>
        <div class="floating-shape shape-2"></div>
        <div class="floating-shape shape-3"></div>
        <div class="floating-shape shape-4"></div>
        <div class="floating-shape shape-5"></div>
    </div>

    <!-- Enhanced Gradient Overlays -->
    <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-black/20 to-transparent"></div>
    <div class="absolute inset-0 bg-gradient-to-br from-blue-600/30 via-transparent to-purple-600/20"></div>

    <!-- Main Content Card -->
    <div id="heroCard" 
         class="relative max-w-xl mx-auto p-8 rounded-2xl 
                transition-all duration-500 ease-out 
                animate-heroFloat glass-card z-10 "
         style="transform-style: preserve-3d;">
        
        <!-- Glowing Border Effect -->
        <div class="absolute inset-0 rounded-xl bg-gradient-to-r from-blue-400 via-purple-500 to-cyan-400 opacity-50 blur-sm animate-pulse-glow"></div>
        
        <!-- Content -->
        <div class="relative z-10">
            <div class="mb-8">
                <h2 class="text-xl md:text-2xl font-black mb-6 text-shimmer
                           drop-shadow-[4px_4px_12px_rgba(0,0,0,0.7)]
                           tracking-tight leading-none">
                    Seputar Jaringan TI
                </h2>
                
                <div class="w-24 h-1 bg-gradient-to-r from-cyan-400 to-blue-500 mx-auto mb-6 rounded-full animate-width-pulse"></div>
                
                <p class="text-md md:text-lg text-blue-100 font-light leading-relaxed
                          drop-shadow-[2px_2px_8px_rgba(0,0,0,0.6)] max-w-3xl mx-auto">
                    PT PLN Batam Gardu Induk Sei Baloi dan PLTD Baloi
                </p>
            </div>
        </div>
    </div>

</section>

<style>
/* Add these styles to your existing <style> section */

/* Particle animations */
.particle {
    position: absolute;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    pointer-events: none;
}

.particle-1 {
    width: 8px;
    height: 8px;
    top: 20%;
    left: 10%;
    animation: float-particle 15s linear infinite;
}

.particle-2 {
    width: 12px;
    height: 12px;
    top: 60%;
    left: 80%;
    animation: float-particle 12s linear infinite reverse;
}

.particle-3 {
    width: 6px;
    height: 6px;
    top: 30%;
    right: 20%;
    animation: float-particle 18s linear infinite;
}

.particle-4 {
    width: 10px;
    height: 10px;
    bottom: 30%;
    left: 70%;
    animation: float-particle 20s linear infinite reverse;
}

.particle-5 {
    width: 14px;
    height: 14px;
    top: 80%;
    left: 30%;
    animation: float-particle 16s linear infinite;
}

.particle-6 {
    width: 4px;
    height: 4px;
    top: 10%;
    right: 40%;
    animation: float-particle 14s linear infinite reverse;
}

@keyframes float-particle {
    0% {
        transform: translateY(0) rotate(0deg);
        opacity: 0;
    }
    10% {
        opacity: 1;
    }
    90% {
        opacity: 1;
    }
    100% {
        transform: translateY(-100vh) rotate(360deg);
        opacity: 0;
    }
}

/* Floating geometric shapes */
.floating-shape {
    position: absolute;
    background: linear-gradient(45deg, rgba(59, 130, 246, 0.1), rgba(147, 51, 234, 0.1));
    backdrop-filter: blur(5px);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.shape-1 {
    width: 60px;
    height: 60px;
    top: 15%;
    left: 15%;
    border-radius: 12px;
    animation: float-shape 8s ease-in-out infinite;
}

.shape-2 {
    width: 40px;
    height: 40px;
    top: 70%;
    right: 20%;
    border-radius: 50%;
    animation: float-shape 10s ease-in-out infinite reverse;
}

.shape-3 {
    width: 80px;
    height: 80px;
    bottom: 20%;
    left: 10%;
    border-radius: 16px;
    animation: float-shape 12s ease-in-out infinite;
}

.shape-4 {
    width: 30px;
    height: 30px;
    top: 40%;
    right: 15%;
    border-radius: 50%;
    animation: float-shape 9s ease-in-out infinite reverse;
}

.shape-5 {
    width: 50px;
    height: 50px;
    top: 25%;
    left: 75%;
    border-radius: 8px;
    animation: float-shape 11s ease-in-out infinite;
}

@keyframes float-shape {
    0%, 100% {
        transform: translateY(0px) rotate(0deg);
    }
    50% {
        transform: translateY(-30px) rotate(180deg);
    }
}

/* Glass card effect */
.glass-card {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
}

/* Text shimmer effect */
.text-shimmer {
    background: linear-gradient(120deg, #ffffff 0%, #e0e7ff 30%, #c7d2fe 50%, #a5b4fc 70%, #ffffff 100%);
    background-size: 200% 200%;
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
    animation: shimmer 3s ease-in-out infinite;
}

@keyframes shimmer {
    0%, 100% {
        background-position: 0% 50%;
    }
    50% {
        background-position: 100% 50%;
    }
}

/* Glowing pulse animation */
@keyframes pulse-glow {
    0%, 100% {
        opacity: 0.3;
        transform: scale(1);
    }
    50% {
        opacity: 0.7;
        transform: scale(1.02);
    }
}

.animate-pulse-glow {
    animation: pulse-glow 4s ease-in-out infinite;
}

/* Width pulse for divider */
@keyframes width-pulse {
    0%, 100% {
        width: 96px;
    }
    50% {
        width: 120px;
    }
}

.animate-width-pulse {
    animation: width-pulse 3s ease-in-out infinite;
}

/* Hero buttons */
.hero-btn {
    position: relative;
    padding: 14px 32px;
    border-radius: 12px;
    font-weight: 600;
    font-size: 16px;
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    overflow: hidden;
    border: 2px solid transparent;
}

.hero-btn-primary {
    background: linear-gradient(135deg, #3b82f6, #1e40af);
    color: white;
    box-shadow: 0 8px 30px rgba(59, 130, 246, 0.4);
}

.hero-btn-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 40px rgba(59, 130, 246, 0.6);
}

.hero-btn-secondary {
    background: rgba(255, 255, 255, 0.1);
    color: white;
    border: 2px solid rgba(255, 255, 255, 0.3);
    backdrop-filter: blur(10px);
}

.hero-btn-secondary:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: translateY(-3px);
    box-shadow: 0 12px 40px rgba(255, 255, 255, 0.2);
}

/* Button shimmer effect */
.hero-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
    transition: all 0.6s;
}

.hero-btn:hover::before {
    left: 100%;
}

/* Statistics cards */
.stat-card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 16px;
    padding: 24px 16px;
    text-align: center;
    transition: all 0.3s ease;
}



/* Scroll indicator animations */
@keyframes bounce-slow {
    0%, 100% {
        transform: translateY(0) translateX(-50%);
    }
    50% {
        transform: translateY(-10px) translateX(-50%);
    }
}

.animate-bounce-slow {
    animation: bounce-slow 2s ease-in-out infinite;
}

@keyframes scroll-indicator {
    0% {
        transform: translateY(0);
        opacity: 1;
    }
    100% {
        transform: translateY(16px);
        opacity: 0;
    }
}

.animate-scroll-indicator {
    animation: scroll-indicator 1.5s ease-in-out infinite;
}

/* Enhanced hero float animation */
@keyframes heroFloat {
    0% { 
        transform: rotateX(2deg) rotateY(-2deg) translateY(0px) scale(1.02); 
    }
    25% { 
        transform: rotateX(-1deg) rotateY(2deg) translateY(-10px) scale(1.03); 
    }
    50% { 
        transform: rotateX(2deg) rotateY(1deg) translateY(0px) scale(1.02); 
    }
    75% { 
        transform: rotateX(-2deg) rotateY(-1deg) translateY(10px) scale(1.01); 
    }
    100% { 
        transform: rotateX(2deg) rotateY(-2deg) translateY(0px) scale(1.02); 
    }
}

.animate-heroFloat {
    animation: heroFloat 8s ease-in-out infinite;
}
</style>

<script>
    cconst hero = document.getElementById("hero3d");
const card = document.getElementById("heroCard");

// Enhanced mouse interaction
hero.addEventListener("mousemove", (e) => {
    card.style.animation = "none";
    const { offsetWidth: w, offsetHeight: h } = hero;
    const { offsetX: x, offsetY: y } = e;

    const rotateX = ((y / h) - 0.5) * 15;
    const rotateY = ((x / w) - 0.5) * 25;

    card.style.transform = `rotateX(${-rotateX}deg) rotateY(${rotateY}deg) scale(1.05)`;
    
    // Add parallax effect to floating shapes
    document.querySelectorAll('.floating-shape').forEach((shape, index) => {
        const speed = (index + 1) * 0.5;
        const xPos = ((x / w) - 0.5) * speed;
        const yPos = ((y / h) - 0.5) * speed;
        shape.style.transform = `translate(${xPos}px, ${yPos}px)`;
    });
});

// Reset animations on mouse leave
hero.addEventListener("mouseleave", () => {
    card.style.animation = "heroFloat 8s ease-in-out infinite";
    document.querySelectorAll('.floating-shape').forEach(shape => {
        shape.style.transform = '';
    });
});

// Add button click effects
document.querySelectorAll('.hero-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
        const ripple = document.createElement('div');
        const rect = this.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = e.clientX - rect.left - size / 2;
        const y = e.clientY - rect.top - size / 2;
        
        ripple.style.cssText = `
            position: absolute;
            width: ${size}px;
            height: ${size}px;
            left: ${x}px;
            top: ${y}px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            transform: scale(0);
            animation: ripple 0.6s linear;
            pointer-events: none;
        `;
        
        this.appendChild(ripple);
        setTimeout(() => ripple.remove(), 600);
    });
});
</script>


<!-- disini woi -->

<!-- Slideshow Atas Hero Section -->
<style scoped>
        /* TV Slideshow Scoped Styles - Hanya untuk komponen ini */
        .tv-slideshow * {
            box-sizing: border-box;
        }

        .tv-slideshow {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            margin: 40px auto;
            max-width: 1300px;
            height: 100%;
            max-height: 700px;
        }

        /* TV Container */
        .tv-slideshow .tv-container {
            background: linear-gradient(145deg, #2c2c2c, #1a1a1a);
            padding: 40px;
            border-radius: 25px;
            box-shadow: 
                0 20px 60px rgba(0,0,0,0.4),
                inset 0 1px 0 rgba(255,255,255,0.1),
                inset 0 -1px 0 rgba(0,0,0,0.3);
            position: relative;
            max-width: 1300px;
            width: 100%;
            max-height: 700px;
            height: 100%;
        }

        /* TV Brand */
        .tv-slideshow .tv-brand {
            position: absolute;
            top: 15px;
            right: 50px;
            color: #888;
            font-size: 14px;
            font-weight: bold;
            letter-spacing: 2px;
        }

        /* TV Screen */
        .tv-slideshow .tv-screen {
            background: #000;
            border-radius: 15px;
            padding: 8px;
            position: relative;
            overflow: hidden;
            box-shadow: 
                inset 0 0 50px rgba(0,0,0,0.8),
                inset 0 0 20px rgba(0,100,255,0.1);
        }

        /* Screen Content */
        .tv-slideshow .screen-content {
            position: relative;
            width: 100%;
            height: 100%;
            border-radius: 8px;
            overflow: hidden;
            background: #000;
        }

        /* Slide */
        .tv-slideshow .slide {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .tv-slideshow .slide.active {
            opacity: 1;
            z-index: 2;
        }

        .tv-slideshow .slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }

        /* Text Overlay */
        .tv-slideshow .slide-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(
                to top,
                rgba(0,0,0,0.9) 0%,
                rgba(0,0,0,0.6) 40%,
                rgba(0,0,0,0.3) 70%,
                transparent 100%
            );
            display: flex;
            flex-direction: column;
            justify-content: end;
            padding: 40px;
            color: white;
        }

        .tv-slideshow .slide-title {
            font-size: 2.5em;
            font-weight: 900;
            margin-bottom: 15px;
            text-shadow: 0 4px 8px rgba(0,0,0,0.8);
            line-height: 1.2;
        }

        .tv-slideshow .slide-description {
            font-size: 1.2em;
            margin-bottom: 10px;
            opacity: 0.9;
            text-shadow: 0 2px 4px rgba(0,0,0,0.8);
            line-height: 1.5;
        }

        .tv-slideshow .slide-author {
            font-size: 1em;
            opacity: 0.7;
            font-style: italic;
            text-shadow: 0 2px 4px rgba(0,0,0,0.8);
        }

        /* TV Controls */
        .tv-slideshow .tv-controls {
            position: absolute;
            right: -60px;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .tv-slideshow .control-btn {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(145deg, #333, #222);
            border: 3px solid #444;
            color: #fff;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        }

        .tv-slideshow .control-btn:hover {
            background: linear-gradient(145deg, #444, #333);
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(0,0,0,0.4);
        }

        .tv-slideshow .control-btn:active {
            transform: scale(0.95);
        }

        /* Navigation Buttons */
        .tv-slideshow .nav-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255,255,255,0.1);
            border: 2px solid rgba(255,255,255,0.2);
            border-radius: 50%;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            font-size: 20px;
            z-index: 10;
        }

        .tv-slideshow .nav-btn:hover {
            background: rgba(255,255,255,0.2);
            transform: translateY(-50%) scale(1.1);
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
        }

        .tv-slideshow .prev-btn {
            left: 20px;
        }

        .tv-slideshow .next-btn {
            right: 20px;
        }

        /* Dots Indicator */
        .tv-slideshow .dots-container {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 12px;
            z-index: 10;
        }

        .tv-slideshow .dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(255,255,255,0.3);
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid rgba(255,255,255,0.5);
        }

        .tv-slideshow .dot.active {
            background: #00ff88;
            box-shadow: 0 0 15px rgba(0,255,136,0.6);
            transform: scale(1.3);
        }

        /* TV Static Effect */
        .tv-slideshow .tv-static {
            position: absolute;
            inset: 0;
            opacity: 0.02;
            background-image: 
                repeating-linear-gradient(
                    0deg,
                    transparent,
                    transparent 2px,
                    rgba(255,255,255,0.03) 2px,
                    rgba(255,255,255,0.03) 4px
                );
            pointer-events: none;
            z-index: 5;
        }

        /* Channel Info */
        .tv-slideshow .channel-info {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(0,0,0,0.7);
            padding: 8px 15px;
            border-radius: 20px;
            color: #00ff88;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            z-index: 10;
            backdrop-filter: blur(5px);
        }

        /* Power LED */
        .tv-slideshow .power-led {
            position: absolute;
            bottom: 20px;
            right: 30px;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #00ff00;
            box-shadow: 0 0 10px #00ff00;
            animation: tv-pulse 2s infinite;
        }

        @keyframes tv-pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }

        /* Animation Effects */
        .tv-slideshow .anim-slide-in { transform: translateX(0); opacity: 1; }
        .tv-slideshow .anim-slide-out { transform: translateX(-100%); opacity: 0; }
        .tv-slideshow .anim-zoom-in { transform: scale(1); opacity: 1; }
        .tv-slideshow .anim-zoom-out { transform: scale(0.8); opacity: 0; }
        .tv-slideshow .anim-fade-in { opacity: 1; }
        .tv-slideshow .anim-fade-out { opacity: 0; }

        /* Responsive */
        @media (max-width: 768px) {
            .tv-slideshow .tv-container {
                padding: 20px;
                margin: 10px;
            }

            .tv-slideshow .screen-content {
                height: 300px;
            }

            .tv-slideshow .tv-controls {
                position: static;
                flex-direction: row;
                justify-content: center;
                margin-top: 20px;
                transform: none;
            }

            .tv-slideshow .slide-title {
                font-size: 1.8em;
            }

            .tv-slideshow .slide-description {
                font-size: 1em;
            }

            .tv-slideshow .tv-brand {
                display: none;
            }
        }
    </style>

    <!-- TV Slideshow Component -->
    <div class="tv-slideshow">
        <div class="tv-container">
            <div class="tv-brand">Jaringan TI</div>
            <div class="power-led"></div>
            
            <div class="tv-screen">
                <div class="screen-content" id="slideshow-container">
                    <!-- Channel Info -->
                    <div class="channel-info">CH 1</div>
                    
                    <!-- TV Static Effect -->
                    <div class="tv-static"></div>
                    
                    <!-- Slides -->
                    @if(isset($slides) && $slides->count() > 0)
                        @foreach($slides as $index => $slide)
                        <div class="slide {{ $index == 0 ? 'active' : '' }}">
                            <img src="{{ asset('admin/storage/' . $slide->content_photo) }}" 
                                 alt="{{ $slide->content_title }}" 
                                 onerror="this.src='https://images.unsplash.com/photo-1518837695005-2083093ee35b?w=800&h=500&fit=crop'">
                            <div class="slide-overlay">
                                <h3 class="slide-title">{{ $slide->content_title }}</h3>
                                <p class="slide-description">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($slide->content_description ?? ''), 100, '...') }}
                                </p>
                                <p class="slide-author">{{ $slide->user_id ? 'Oleh: ' . $slide->user->name : '' }}</p>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <!-- Default slides when no user content -->
                        <div class="slide active">
                            <img src="https://images.unsplash.com/photo-1518837695005-2083093ee35b?w=800&h=500&fit=crop" alt="Demo Slide 1">
                            <div class="slide-overlay">
                                <h3 class="slide-title">Belum Ada Konten</h3>
                                <p class="slide-description">Silakan tambahkan foto dan konten untuk ditampilkan di slideshow ini</p>
                                <p class="slide-author">Admin</p>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Navigation Buttons -->
                    <button class="nav-btn prev-btn" id="tvPrevBtn">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="nav-btn next-btn" id="tvNextBtn">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                    
                    <!-- Dots Indicator -->
                    <div class="dots-container" id="tvDotsContainer">
                        @if(isset($slides) && $slides->count() > 0)
                            @for($i = 0; $i < $slides->count(); $i++)
                                <span class="dot {{ $i == 0 ? 'active' : '' }}" data-index="{{ $i }}"></span>
                            @endfor
                        @else
                            <span class="dot active" data-index="0"></span>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- TV Controls (Hide if only one slide or no slides) -->
            @if(!isset($slides) || $slides->count() > 1)
            <div class="tv-controls">
                <button class="control-btn" id="tvPowerBtn" title="Power">
                    <i class="fas fa-power-off"></i>
                </button>
                


            </div>
            @endif
        </div>
    </div>

    <script>
        (function() {
            // Namespace untuk mencegah konflik dengan script lain
            const TVSlideshow = {
                init: function() {
                    this.slides = document.querySelectorAll('.tv-slideshow .slide');
                    this.dots = document.querySelectorAll('.tv-slideshow .dot');
                    this.prevBtn = document.getElementById('tvPrevBtn');
                    this.nextBtn = document.getElementById('tvNextBtn');
                    this.powerBtn = document.getElementById('tvPowerBtn');
                    this.channelInfo = document.querySelector('.tv-slideshow .channel-info');
                    this.dotsContainer = document.getElementById('tvDotsContainer');
                    
                    this.total = this.slides.length;
                    this.currentIndex = 0;
                    this.isPlaying = true;
                    this.autoplayTimer = null;
                    
                    this.effects = [
                        { in: 'anim-slide-in', out: 'anim-slide-out' },
                        { in: 'anim-zoom-in', out: 'anim-zoom-out' },
                        { in: 'anim-fade-in', out: 'anim-fade-out' }
                    ];

                    // Hide navigation if only one slide or no slides
                    if (this.total <= 1) {
                        if (this.prevBtn) this.prevBtn.style.display = 'none';
                        if (this.nextBtn) this.nextBtn.style.display = 'none';
                        if (this.dotsContainer) this.dotsContainer.style.display = 'none';
                        return; // Exit if no slideshow needed
                    }

                    this.bindEvents();
                    this.resetAutoplay();
                },

                getRandomEffect: function() {
                    return this.effects[Math.floor(Math.random() * this.effects.length)];
                },

                showSlide: function(index, manual = false) {
                    const currentSlide = this.slides[this.currentIndex];
                    const nextSlide = this.slides[index];
                    const effect = this.getRandomEffect();

                    // Remove all animation classes
                    this.slides.forEach(slide => {
                        slide.classList.remove('active');
                        this.effects.forEach(e => {
                            slide.classList.remove(e.in, e.out);
                        });
                    });

                    // Apply transition effect
                    if (currentSlide !== nextSlide) {
                        currentSlide.classList.add(effect.out);
                        setTimeout(() => {
                            nextSlide.classList.add('active', effect.in);
                        }, 100);
                    } else {
                        nextSlide.classList.add('active', effect.in);
                    }

                    // Update dots
                    this.dots.forEach((dot, i) => {
                        dot.classList.toggle('active', i === index);
                    });

                    // Update channel info
                    if (this.channelInfo) {
                        this.channelInfo.textContent = `CH ${String(index + 1).padStart(2, '0')}`;
                    }

                    this.currentIndex = index;

                    if (manual) {
                        this.resetAutoplay();
                    }
                },

                nextSlide: function() {
                    const nextIndex = (this.currentIndex + 1) % this.slides.length;
                    this.showSlide(nextIndex);
                },

                prevSlide: function() {
                    const prevIndex = (this.currentIndex - 1 + this.slides.length) % this.slides.length;
                    this.showSlide(prevIndex);
                },

                resetAutoplay: function() {
                    if (this.autoplayTimer) {
                        clearInterval(this.autoplayTimer);
                    }
                    if (this.isPlaying) {
                        this.autoplayTimer = setInterval(() => this.nextSlide(), 5000);
                    }
                },

                togglePower: function() {
                    const screenContent = document.getElementById('slideshow-container');
                    const powerLed = document.querySelector('.tv-slideshow .power-led');
                    
                    if (this.isPlaying) {
                        // Turn off
                        screenContent.style.opacity = '0.1';
                        if (powerLed) {
                            powerLed.style.background = '#ff0000';
                            powerLed.style.boxShadow = '0 0 10px #ff0000';
                        }
                        clearInterval(this.autoplayTimer);
                        this.isPlaying = false;
                    } else {
                        // Turn on
                        screenContent.style.opacity = '1';
                        if (powerLed) {
                            powerLed.style.background = '#00ff00';
                            powerLed.style.boxShadow = '0 0 10px #00ff00';
                        }
                        this.resetAutoplay();
                        this.isPlaying = true;
                    }
                },

                bindEvents: function() {
                    // Navigation buttons
                    if (this.nextBtn) {
                        this.nextBtn.addEventListener('click', () => {
                            const nextIndex = (this.currentIndex + 1) % this.slides.length;
                            this.showSlide(nextIndex, true);
                        });
                    }

                    if (this.prevBtn) {
                        this.prevBtn.addEventListener('click', () => {
                            const prevIndex = (this.currentIndex - 1 + this.slides.length) % this.slides.length;
                            this.showSlide(prevIndex, true);
                        });
                    }

                    // Dots navigation
                    this.dots.forEach((dot, index) => {
                        dot.addEventListener('click', () => {
                            this.showSlide(index, true);
                        });
                    });

                    // Power button
                    if (this.powerBtn) {
                        this.powerBtn.addEventListener('click', () => this.togglePower());
                    }

                    // Keyboard controls (hanya untuk TV slideshow)
                    document.addEventListener('keydown', (e) => {
                        // Check if TV slideshow is visible
                        const tvSlideshow = document.querySelector('.tv-slideshow');
                        if (!tvSlideshow || !this.isElementInViewport(tvSlideshow)) return;

                        switch(e.key) {
                            case 'ArrowLeft':
                                e.preventDefault();
                                const prevIndex = (this.currentIndex - 1 + this.slides.length) % this.slides.length;
                                this.showSlide(prevIndex, true);
                                break;
                            case 'ArrowRight':
                                e.preventDefault();
                                const nextIndex = (this.currentIndex + 1) % this.slides.length;
                                this.showSlide(nextIndex, true);
                                break;
                            case ' ':
                                e.preventDefault();
                                if (this.powerBtn) this.togglePower();
                                break;
                        }
                    });
                },

                isElementInViewport: function(el) {
                    const rect = el.getBoundingClientRect();
                    return (
                        rect.top >= 0 &&
                        rect.left >= 0 &&
                        rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                        rect.right <= (window.innerWidth || document.documentElement.clientWidth)
                    );
                }
            };

            // Initialize when DOM is ready
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', () => TVSlideshow.init());
            } else {
                TVSlideshow.init();
            }
        })();
    </script>

<!-- endnya disini -->

<!--  -->

<!--  -->

<!-- Hero Section -->
<section class="flex p-6 lg:p-8 items-center lg:justify-center flex-col bg-white">
  <div class="max-w-4xl mx-auto px-4 text-center rounded-lg w-full">
    <div class="flex items-center w-full">
      <!-- Garis kiri -->
      <span class="flex-grow border-t-2 border-gray-300"></span>

      <!-- Tombol di tengah (glassmorphism) -->
      <div class="px-4 shrink-0 z-10">
        <a href="{{ route('contents') }}"
           class="inline-flex items-center px-5 py-2 rounded-xl text-sm font-semibold
                  text-[#08306b] relative overflow-hidden btn-modern"
           aria-label="Semua Konten">
            <span class="flex items-center gap-2 ">
                <i class="fas fa-th-list"></i>
                <span>Semua Konten</span>
            </span>
            <span class="glass-overlay" aria-hidden="true"></span>
        </a>
      </div>

      <!-- Garis kanan -->
      <span class="flex-grow border-t-2 border-gray-300"></span>
    </div>
  </div>
</section>



<!-- Main Content -->
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Aplikasi Jaringan TI -->
    <section class="relative overflow-hidden bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 rounded-3xl shadow-2xl p-8 mb-16 group">
        <!-- Animated background elements -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-xl animate-pulse"></div>
            <div class="absolute -bottom-10 -left-10 w-60 h-60 bg-white/5 rounded-full blur-2xl"></div>
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-80 h-80 bg-gradient-to-r from-white/5 to-transparent rounded-full blur-3xl group-hover:scale-110 transition-transform duration-1000"></div>
        </div>
        
        <div class="relative z-10">
            <div class="text-center mb-12">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl mb-4 group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-rocket text-white text-2xl"></i>
                </div>
                <h3 class="text-3xl font-bold text-white mb-3 group-hover:text-blue-100 transition-colors duration-300">
                    Aplikasi Jaringan TI
                </h3>
                <p class="text-blue-100 text-lg max-w-2xl mx-auto">
                    Akses cepat ke semua tools dan aplikasi yang Anda butuhkan untuk mengelola infrastruktur TI
                </p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                @foreach($apps as $app)
                    <a href="{{ $app->app_url }}" target="_blank" 
                       class="group/app relative overflow-hidden bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl p-6 hover:bg-white/20 hover:border-white/40 hover:scale-105 hover:shadow-2xl transition-all duration-300 cursor-pointer">
                        
                        <!-- Hover effect overlay -->
                        <div class="absolute inset-0 bg-gradient-to-r from-white/10 to-transparent opacity-0 group-hover/app:opacity-100 transition-opacity duration-300"></div>
                        
                        <div class="relative z-10">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center mr-4 group-hover/app:bg-white/30 group-hover/app:scale-110 transition-all duration-300">
                                    {!! $app->app_icon !!}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-white font-semibold text-lg truncate group-hover/app:text-blue-100 transition-colors duration-300">
                                        {{ $app->app_name }}
                                    </h4>
                                    <p class="text-blue-200 text-sm opacity-80">
                                        Aplikasi TI
                                    </p>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-blue-100 text-sm font-medium group-hover/app:text-white transition-colors duration-300">
                                    Buka Aplikasi
                                </span>
                                <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center group-hover/app:bg-white/30 group-hover/app:translate-x-1 transition-all duration-300">
                                    <i class="fas fa-external-link-alt text-white text-sm"></i>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Shine effect on hover -->
                        <div class="absolute inset-0 -translate-x-full group-hover/app:translate-x-full bg-gradient-to-r from-transparent via-white/20 to-transparent transition-transform duration-1000 ease-out"></div>
                    </a>
                @endforeach
            </div>
            
            <!-- Quick stats or additional info -->
        </div>
    </section>

    <!-- Kategori Knowledge Base -->
    <section class="relative overflow-hidden bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 rounded-3xl shadow-2xl p-8 mb-16 group">
        <!-- Background pattern -->
        <div class="absolute inset-0 opacity-5">
            <div class="absolute inset-0" style="background-image: radial-gradient(circle at 1px 1px, rgb(59 130 246) 1px, transparent 0); background-size: 20px 20px;"></div>
        </div>
        
        <!-- Floating elements -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute top-10 right-10 w-32 h-32 bg-gradient-to-r from-blue-400/20 to-purple-400/20 rounded-full blur-xl animate-pulse"></div>
            <div class="absolute bottom-10 left-10 w-24 h-24 bg-gradient-to-r from-indigo-400/20 to-blue-400/20 rounded-full blur-lg" style="animation: float 6s ease-in-out infinite;"></div>
        </div>
        
        <div class="relative z-10">
            <div class="text-center mb-12">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl mb-4 shadow-lg group-hover:scale-110 group-hover:shadow-xl transition-all duration-300">
                    <i class="fas fa-book-open text-blue-800 text-2xl"></i>
                </div>
                <h3 class="text-3xl font-bold bg-gradient-to-r text-blue-600  bg-clip-text mb-3">
                    Kategori Knowledge Base
                </h3>
                <p class="text-gray-600 text-lg max-w-3xl mx-auto leading-relaxed">
                    Jelajahi koleksi knowledge base yang terorganisir berdasarkan kategori untuk menemukan solusi dengan cepat dan efisien
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">
                @foreach($categories as $category)
                    <div class="group/card relative overflow-hidden bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 cursor-pointer border border-gray-100 hover:border-blue-200 category-card hover:scale-[1.02]" 
                         data-category="{{ $category->field_id }}" 
                         data-url="{{ route('kbs', $category->field_id) }}">
                        
                        <!-- Hover gradient overlay -->
                        <div class="absolute inset-0 bg-gradient-to-br from-blue-500/5 to-purple-500/5 opacity-0 group-hover/card:opacity-100 transition-opacity duration-300"></div>
                        
                        <!-- Content -->
                        <div class="relative p-8">
                            <!-- Header -->
                            <div class="flex items-start mb-6">
                                <div class="relative">
                                    <div class="w-14 h-14 bg-gradient-to-br from-blue-100 to-blue-200 rounded-2xl flex items-center justify-center mr-4 group-hover/card:from-blue-500 group-hover/card:to-purple-500 group-hover/card:scale-110 group-hover/card:shadow-lg transition-all duration-300">
                                        <i class="fas fa-folder text-blue-600 text-xl group-hover/card:text-white transition-colors duration-300"></i>
                                    </div>
                                    <!-- Notification badge -->
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-bold text-gray-900 text-lg mb-1 group-hover/card:text-blue-700 transition-colors duration-300 line-clamp-2">
                                        {{ $category->field_name }}
                                    </h4>
                                    <p class="text-sm text-gray-500 group-hover/card:text-blue-600 transition-colors duration-300 font-medium">
                                        {{ $category->knowledgebase->count() }} Knowledge Base
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Description or preview -->
                            <div class="mb-6">
                                <p class="text-gray-600 text-sm leading-relaxed line-clamp-3">
                                    Temukan panduan lengkap, tutorial, dan dokumentasi untuk {{ strtolower($category->field_name) }}. 
                                    Akses knowledge base yang telah dikurasi dengan baik oleh tim ahli.
                                </p>
                            </div>
                            
                            <!-- Footer -->
                            <div class="flex items-center justify-between pt-4 border-t border-gray-100 group-hover/card:border-blue-200 transition-colors duration-300">
                                <div class="flex items-center text-blue-600 text-sm font-semibold group-hover/card:text-blue-700 transition-colors duration-300">
                                    <span>Eksplorasi Knowledge</span>
                                    <i class="fas fa-arrow-right ml-2 group-hover/card:translate-x-1 transition-transform duration-300"></i>
                                </div>
                                <div class="flex space-x-1">
                                    <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                                    <div class="w-2 h-2 bg-blue-400 rounded-full animate-pulse" style="animation-delay: 0.2s;"></div>
                                    <div class="w-2 h-2 bg-purple-400 rounded-full animate-pulse" style="animation-delay: 0.4s;"></div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Shine effect -->
                        <div class="absolute inset-0 -translate-x-full group-hover/card:translate-x-full bg-gradient-to-r from-transparent via-white/30 to-transparent transition-transform duration-1000 ease-out"></div>
                    </div>
                @endforeach
            </div>
            
            <!-- Summary stats -->
            
        </div>
    </section>

</main>

<style>
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Enhanced hover effects */
.group:hover .animate-pulse {
    animation-duration: 1s;
}

/* Smooth transitions for all interactive elements */
.category-card {
    transform-origin: center;
    transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.category-card:hover {
    transform: translateY(-4px) scale(1.02);
}

/* Custom scrollbar for overflow content */
.overflow-auto::-webkit-scrollbar {
    width: 4px;
}

.overflow-auto::-webkit-scrollbar-track {
    background: transparent;
}

.overflow-auto::-webkit-scrollbar-thumb {
    background: rgba(59, 130, 246, 0.3);
    border-radius: 2px;
}

.overflow-auto::-webkit-scrollbar-thumb:hover {
    background: rgba(59, 130, 246, 0.5);
}
</style>


    <!-- Footer -->
    

    <!-- Modal for Article Detail -->
    
    <script>
        // Filter functionality
        const filterBtns = document.querySelectorAll('.filter-btn');
        
        filterBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                // Remove active class from all buttons
                filterBtns.forEach(b => {
                    b.classList.remove('active', 'bg-blue-600', 'text-white');
                    b.classList.add('bg-gray-200', 'text-gray-700');
                });
                
                // Add active class to clicked button
                this.classList.add('active', 'bg-blue-600', 'text-white');
                this.classList.remove('bg-gray-200', 'text-gray-700');
                
                const filter = this.getAttribute('data-filter');
                
                articles.forEach(article => {
                    const category = article.getAttribute('data-category');
                    
                    if (filter === 'all' || category === filter) {
                        article.style.display = 'block';
                    } else {
                        article.style.display = 'none';
                    }
                });
            });
        });

        // Category card click
        const categoryCards = document.querySelectorAll('.category-card');
        
        categoryCards.forEach(card => {
            card.addEventListener('click', function() {
                const url = this.getAttribute('data-url');
                if (url) {
                    window.location.href = url;
                }
            });
        });

        // Modal functionality
        
    </script>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</body>
</html>