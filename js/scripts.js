document.addEventListener('DOMContentLoaded', () => {
    // Seleciona os elementos relevantes do DOM
    const toggleButton = document.querySelector('.theme-toggle')
    const body = document.body
    const systemIcon = document.querySelector('.system-icon')
    const lightIcon = document.querySelector('.light-icon')
    const darkIcon = document.querySelector('.dark-icon')

    // Define os possíveis estados do tema
    const themes = ['system', 'light', 'dark']
    const icons = {
        system: systemIcon,
        light: lightIcon,
        dark: darkIcon
    }

    // Função para aplicar o tema visualmente (classe no body)
    const applyVisualTheme = (theme) => {
        let visualTheme = theme // Por padrão, o tema visual é o tema escolhido

        // Se o tema for 'system', verifica a preferência do sistema
        if (theme === 'system') {
            const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches
            visualTheme = prefersDark ? 'dark' : 'light' // Define o tema visual baseado na preferência do sistema
        }

        // Aplica a classe 'light-mode' ou a remove, baseado no tema visual
        if (visualTheme === 'light') {
            body.classList.add('light-mode')
        } else {
            body.classList.remove('light-mode')
        }
    }

    // Função para atualizar o ícone visível no botão
    const updateButtonIcon = (theme) => {
        // Esconde todos os ícones primeiro
        systemIcon.style.display = 'none'
        lightIcon.style.display = 'none'
        darkIcon.style.display = 'none'

        // Mostra o ícone correspondente ao tema ATUALMENTE selecionado
        if (icons[theme]) {
            icons[theme].style.display = 'inline'
        } else {
            // Fallback para o ícone do sistema se algo der errado
            icons['system'].style.display = 'inline'
        }
    }

    // Função principal para definir o tema
    const setTheme = (theme) => {
        // Verifica se o tema é válido, senão usa 'system' como padrão
        if (!themes.includes(theme)) {
            theme = 'system'
        }
        // Aplica o tema visual (classe no body)
        applyVisualTheme(theme)
        // Atualiza o ícone do botão para refletir o tema selecionado
        updateButtonIcon(theme)
        // Salva a preferência no localStorage
        localStorage.setItem('theme', theme)
        // Atualiza a variável global que armazena o tema atual
        currentTheme = theme
    }

    // --- Inicialização ---
    // Obtém o tema salvo no localStorage ou define 'system' como padrão
    let currentTheme = localStorage.getItem('theme') || 'system'
    // Define o tema inicial ao carregar a página
    setTheme(currentTheme)

    // --- Event Listeners ---
    // Evento de clique no botão de alternância
    toggleButton.addEventListener('click', () => {
        // Encontra o índice do tema atual no array de temas
        const currentIndex = themes.indexOf(currentTheme)
        // Calcula o índice do próximo tema, ciclando de volta ao início se necessário
        const nextIndex = (currentIndex + 1) % themes.length
        // Obtém o nome do próximo tema
        const nextTheme = themes[nextIndex]
        // Define o novo tema
        setTheme(nextTheme)
    })

    // Ouve mudanças na preferência de cor do sistema
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
        // Se o tema atual for 'system', reaplica o tema visual para refletir a mudança
        if (currentTheme === 'system') {
            applyVisualTheme('system')
        }
    })
})
