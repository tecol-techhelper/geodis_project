<button
    {{ $attributes->merge([
        'type' => 'submit',
        'class' => 'inline-flex 
                            items-center 
                            justify-center 
                            px-4 
                            py-2 bg-white 
                            dark:bg-white 
                            border-[1px] 
                            border-blue-700
                            rounded-md
                            font-semibold
                            text-xs
                            text-blue-700
                            dark:text-blue-700
                            uppercase
                            tracking-widest
                            hover:bg-blue-700
                            dark:hover:bg-blue-700
                            hover:shadow-lg
                            hover:text-white
                            dark:hover:text-white
                            dark:hover:bg-white
                            focus-visible:bg-blue-700
                            focus-visible:text-white
                            dark:focus-visible:bg-blue-700
                            dark:focus-visible:text-white
                            active:bg-blue-900
                            dark:active:bg-blue-300
                            focus:outline-none
                            focus-visible:ring-2
                            focus-visible:ring-indigo-500
                            focus-visible:ring-offset-2
                            dark:focus-visible:ring-offset-blue-800
                            transition
                            ease-in-out
                            duration-150',
    ]) }}>
    {{ $slot }}
</button>
