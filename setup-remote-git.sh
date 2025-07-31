#!/bin/bash

# Colori per output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

echo -e "${YELLOW}=== Setup Remote Git Repository ===${NC}"
echo ""

# Richiedi le informazioni all'utente
echo -e "${BLUE}Inserisci le informazioni del server:${NC}"
echo ""

read -p "Username del server: " USERNAME
if [ -z "$USERNAME" ]; then
    echo -e "${RED}ERRORE: Username non può essere vuoto${NC}"
    exit 1
fi

read -p "Indirizzo IP del server: " SERVER_IP
if [ -z "$SERVER_IP" ]; then
    echo -e "${RED}ERRORE: IP del server non può essere vuoto${NC}"
    exit 1
fi

read -p "Nome del dominio (es: example.com): " DOMAIN
if [ -z "$DOMAIN" ]; then
    echo -e "${RED}ERRORE: Dominio non può essere vuoto${NC}"
    exit 1
fi

echo ""
echo -e "${YELLOW}Configurazione:${NC}"
echo -e "${YELLOW}Username: ${USERNAME}${NC}"
echo -e "${YELLOW}Server IP: ${SERVER_IP}${NC}"
echo -e "${YELLOW}Domain: ${DOMAIN}${NC}"
echo ""

read -p "Procedere con la configurazione? (y/n): " CONFIRM
if [ "$CONFIRM" != "y" ] && [ "$CONFIRM" != "Y" ]; then
    echo -e "${YELLOW}Operazione annullata${NC}"
    exit 0
fi

echo -e "${GREEN}Connessione al server remoto...${NC}"

# Comando SSH che esegue tutti i passaggi sul server remoto
ssh ${USERNAME}@${SERVER_IP} << EOF
    echo "=== Navigazione nella cartella private ==="
    cd /home/${USERNAME}/web/${DOMAIN}/private || { echo "Errore: impossibile accedere alla cartella /home/${USERNAME}/web/${DOMAIN}/private"; exit 1; }
    
    echo "=== Creazione cartella site.git ==="
    mkdir -p site.git
    cd site.git
    
    echo "=== Inizializzazione repository bare ==="
    git init --bare
    
    echo "=== Creazione hook post-receive ==="
    cd hooks
    
    cat > post-receive << 'HOOK_EOF'
#!/bin/sh
git --work-tree=/home/${USERNAME}/web/${DOMAIN}/public_html --git-dir=/home/${USERNAME}/web/${DOMAIN}/private/site.git checkout -f

cd /home/${USERNAME}/web/${DOMAIN}/public_html && sh deploy.sh 
HOOK_EOF
    
    echo "=== Rendere il file post-receive eseguibile ==="
    chmod +x post-receive
    
    echo "=== Setup completato con successo! ==="
    echo "Repository bare creato in: /home/${USERNAME}/web/${DOMAIN}/private/site.git"
    echo "Hook post-receive configurato e reso eseguibile"
EOF

# Verifica se il comando SSH è andato a buon fine
if [ $? -eq 0 ]; then
    echo -e "${GREEN}=== Setup completato con successo! ===${NC}"
    echo ""
    echo -e "${YELLOW}Per utilizzare questo repository, aggiungi il remote al tuo progetto locale:${NC}"
    echo -e "${GREEN}git remote add live ${USERNAME}@${SERVER_IP}:/home/${USERNAME}/web/${DOMAIN}/private/site.git${NC}"
    echo ""
    echo -e "${YELLOW}Oppure usa l'indirizzo SSH completo:${NC}"
    echo -e "${GREEN}ssh://${USERNAME}@${SERVER_IP}/home/${USERNAME}/web/${DOMAIN}/private/site.git${NC}"
    echo ""
    echo -e "${YELLOW}Per fare il deploy:${NC}"
    echo -e "${GREEN}git push live main${NC}"
    echo ""
    echo -e "${BLUE}=== INDIRIZZO REPOSITORY REMOTO ===${NC}"
    echo -e "${BLUE}ssh://${USERNAME}@${SERVER_IP}/home/${USERNAME}/web/${DOMAIN}/private/site.git${NC}"
else
    echo -e "${RED}=== Errore durante il setup ===${NC}"
    exit 1
fi
